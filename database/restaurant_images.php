<?php
/**
 * Attach unique remote image URLs to restaurants.
 * This keeps cards on URL-based photography while avoiding repeated images.
 */
function appetitus_assign_unique_restaurant_images(array $restaurants): array {
    $fallbackImages = [
        'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&q=80',
        'https://images.unsplash.com/photo-1551218808-94e220e084d2?w=800&q=80',
        'https://images.unsplash.com/photo-1544148103-0773bf10d330?w=800&q=80',
        'https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?w=800&q=80',
        'https://images.unsplash.com/photo-1551183053-bf91a1d81141?w=800&q=80',
        'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=800&q=80',
        'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=800&q=80',
        'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&q=80',
        'https://images.unsplash.com/photo-1466978913421-dad2ebd01d17?w=800&q=80',
        'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=800&q=80',
        'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=800&q=80',
        'https://images.unsplash.com/photo-1514933651103-005eec06c04b?w=800&q=80',
        'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&q=80',
        'https://images.unsplash.com/photo-1515669097368-22e68427d265?w=800&q=80',
        'https://images.unsplash.com/photo-1528605248644-14dd04022da1?w=800&q=80',
        'https://images.unsplash.com/photo-1537047902294-62a40c20a6ae?w=800&q=80',
        'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=800&q=80',
        'https://images.unsplash.com/photo-1543353071-873f17a7a088?w=800&q=80',
        'https://images.unsplash.com/photo-1544025162-d76694265947?w=800&q=80',
        'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&q=80',
        'https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?w=800&q=80',
        'https://images.unsplash.com/photo-1552566626-52f8b828add9?w=800&q=80',
        'https://images.unsplash.com/photo-1553621042-f6e147245754?w=800&q=80',
        'https://images.unsplash.com/photo-1559329007-40df8a9345d8?w=800&q=80',
        'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=800&q=80',
        'https://images.unsplash.com/photo-1567521464027-f127ff144326?w=800&q=80',
        'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=800&q=80',
        'https://images.unsplash.com/photo-1568376794508-ae52c6ab3929?w=800&q=80',
        'https://images.unsplash.com/photo-1569058242567-93de6f36f8eb?w=800&q=80',
        'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=800&q=80',
        'https://images.unsplash.com/photo-1574484284002-952d92456975?w=800&q=80',
        'https://images.unsplash.com/photo-1580554530778-ca36943938b2?w=800&q=80',
        'https://images.unsplash.com/photo-1585238342024-78d387f4a707?w=800&q=80',
        'https://images.unsplash.com/photo-1600891964599-f61ba0e24092?w=800&q=80',
        'https://images.unsplash.com/photo-1600891964092-4316c288032e?w=800&q=80',
        'https://images.unsplash.com/photo-1600891965050-6da6bad77c0f?w=800&q=80',
        'https://images.unsplash.com/photo-1600891963935-9e8c51bd4fd9?w=800&q=80',
        'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=800&q=80',
        'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=800&q=80',
        'https://images.unsplash.com/photo-1604908554027-7836472f4fa4?w=800&q=80',
        'https://images.unsplash.com/photo-1604908812869-3d6043855a7f?w=800&q=80',
        'https://images.unsplash.com/photo-1606787366850-de6330128bfc?w=800&q=80',
        'https://images.unsplash.com/photo-1611599537845-1c7aca0091c0?w=800&q=80',
        'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?w=800&q=80',
        'https://images.unsplash.com/photo-1630409346824-4f0e7b080087?w=800&q=80',
        'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=800&q=80',
        'https://images.unsplash.com/photo-1633321702518-7feccafb94d5?w=800&q=80',
        'https://images.unsplash.com/photo-1645066593554-6c4f5699f441?w=800&q=80',
        'https://images.unsplash.com/photo-1651978595429-5fc2de277d5b?w=800&q=80'
    ];

    $usedImages = [];
    $originalRemoteImages = [];

    foreach ($restaurants as $restaurant) {
        $image = isset($restaurant['image']) ? trim($restaurant['image']) : '';
        if ($image !== '' && preg_match('/^https?:\/\//i', $image) === 1) {
            $originalRemoteImages[$image] = true;
        }
    }

    $fallbackImages = array_values(array_filter($fallbackImages, function ($fallbackImage) use ($originalRemoteImages) {
        return !isset($originalRemoteImages[$fallbackImage]);
    }));

    foreach ($restaurants as &$restaurant) {
        $restaurant['image'] = isset($restaurant['image']) ? trim($restaurant['image']) : '';
        $hasRemoteImage = preg_match('/^https?:\/\//i', $restaurant['image']) === 1;
        $isDuplicate = $restaurant['image'] !== '' && isset($usedImages[$restaurant['image']]);

        if ($restaurant['image'] === '' || !$hasRemoteImage || $isDuplicate) {
            foreach ($fallbackImages as $fallbackImage) {
                if (!isset($usedImages[$fallbackImage])) {
                    $restaurant['image'] = $fallbackImage;
                    break;
                }
            }
        }

        $usedImages[$restaurant['image']] = true;
    }
    unset($restaurant);

    return $restaurants;
}
