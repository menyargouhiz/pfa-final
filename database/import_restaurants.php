<?php
require_once __DIR__ . '/../config/database.php';

$restaurants = [
  [ 'name'=>"Dar El Jeld", 'cuisine'=>"Tunisian", 'category'=>"gastronomique", 'address'=>"5-10 Rue Dar El Jeld, Medina", 'city'=>"Tunis", 'phone'=>"+216 71 560 916", 'priceRange'=>"€€€", 'lat'=>36.7990, 'lng'=>10.1695, 'tags'=>"Palace,Medina,Refined", 'image'=>"https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&q=80", 'description'=>"Housed in an 18th-century palace in the heart of the Medina, Dar El Jeld is the reference for fine dining in Tunis. Refined Tunisian cuisine in an exceptional setting.", 'openHours'=>"Mon-Sat : 12:30–3PM, 7:30–11PM" ],
  [ 'name'=>"L'Escargot", 'cuisine'=>"French", 'category'=>"gastronomique", 'address'=>"Sidi Salem Route", 'city'=>"Bizerte", 'phone'=>"+216 72 431 111", 'priceRange'=>"€€€", 'lat'=>37.2744, 'lng'=>9.8739, 'tags'=>"Vieux Port,Seafood,Elegant", 'image'=>"https://images.unsplash.com/photo-1559339352-11d035aa65de?w=800&q=80", 'description'=>"A historic establishment offering refined French and Mediterranean cuisine near the beautiful old port of Bizerte.", 'openHours'=>"Tue-Sun : 12PM–3PM, 7PM–11PM" ],
  [ 'name'=>"Le Saf Saf", 'cuisine'=>"Tunisian", 'category'=>"brasserie", 'address'=>"Place du Saf Saf", 'city'=>"La Marsa", 'phone'=>"+216 71 743 333", 'priceRange'=>"€", 'lat'=>36.8785, 'lng'=>10.3247, 'tags'=>"Traditional,Brik,Historical", 'image'=>"https://images.unsplash.com/photo-1466978913421-dad2ebd01d17?w=800&q=80", 'description'=>"A legendary outdoor café and eatery under giant trees, serving traditional Brik, lablabi, and mint tea.", 'openHours'=>"Daily : 8AM–11PM" ],
  [ 'name'=>"Dar Zarrouk", 'cuisine'=>"Mediterranean", 'category'=>"gastronomique", 'address'=>"Route de la Corniche", 'city'=>"Sidi Bou Said", 'phone'=>"+216 71 740 591", 'priceRange'=>"€€€", 'lat'=>36.8706, 'lng'=>10.3456, 'tags'=>"Sea View,Terrace,Elegant", 'image'=>"https://images.unsplash.com/photo-1551218808-94e220e084d2?w=800&q=80", 'description'=>"Nestled in the heights of Sidi Bou Said, offering a panoramic terrace overlooking the Gulf of Tunis. Mediterranean cuisine redefined.", 'openHours'=>"Tue-Sun : 12PM–3PM, 7PM–11PM" ],
  [ 'name'=>"El Walima", 'cuisine'=>"Seafood", 'category'=>"fruits-de-mer", 'address'=>"Port Area", 'city'=>"La Goulette", 'phone'=>"+216 71 736 444", 'priceRange'=>"€€", 'lat'=>36.8183, 'lng'=>10.3038, 'tags'=>"Fresh Fish,Port,Family", 'image'=>"https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800&q=80", 'description'=>"A staple in La Goulette, El Walima delights customers with the freshest seafood. Grilled fish and octopus cooked traditionally.", 'openHours'=>"Daily : 11:30AM–10:30PM" ],
  [ 'name'=>"Restaurant de l'Olivier", 'cuisine'=>"Tunisian", 'category'=>"brasserie", 'address'=>"Centre Ville", 'city'=>"Ariana", 'phone'=>"+216 71 710 000", 'priceRange'=>"€€", 'lat'=>36.8625, 'lng'=>10.1956, 'tags'=>"Couscous,Authentic,Cozy", 'image'=>"https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=800&q=80", 'description'=>"A lovely spot in Ariana dedicated to hearty, home-style Tunisian meals and rich couscous variations.", 'openHours'=>"Mon-Sat : 11AM–4PM" ],
  [ 'name'=>"Le Pêcheur", 'cuisine'=>"Seafood", 'category'=>"fruits-de-mer", 'address'=>"Corniche", 'city'=>"Nabeul", 'phone'=>"+216 72 222 333", 'priceRange'=>"€€", 'lat'=>36.4561, 'lng'=>10.7376, 'tags'=>"Beach,Grill,Harissa", 'image'=>"https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800&q=80", 'description'=>"Famous for its fresh catches and local Nabeul harissa spice blend, right next to the beach.", 'openHours'=>"Daily : 12PM–10PM" ],
  [ 'name'=>"L'Oliveraie", 'cuisine'=>"Tunisian", 'category'=>"gastronomique", 'address'=>"Route de Zaghouan", 'city'=>"Zaghouan", 'phone'=>"+216 72 670 123", 'priceRange'=>"€€", 'lat'=>36.4011, 'lng'=>10.1433, 'tags'=>"Nature,Water Spring,Roast Meat", 'image'=>"https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&q=80", 'description'=>"Enjoy roasted lamb and traditional bread baked in clay ovens, surrounded by Zaghouan's natural springs and mountains.", 'openHours'=>"Wed-Sun : 12PM–6PM" ],
  [ 'name'=>"Le Grill Marin", 'cuisine'=>"Seafood", 'category'=>"fruits-de-mer", 'address'=>"Port de Pêche", 'city'=>"Mahdia", 'phone'=>"+216 73 681 000", 'priceRange'=>"€€", 'lat'=>35.5047, 'lng'=>11.0622, 'tags'=>"Sardines,Harbor,Fresh", 'image'=>"https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800&q=80", 'description'=>"Offering the best sardines and squid right as the fishing boats dock in Mahdia's historic harbor.", 'openHours'=>"Daily : 11AM–9PM" ],
  [ 'name'=>"Sfaxian Delights", 'cuisine'=>"Tunisian", 'category'=>"street-food", 'address'=>"Bab Diwan", 'city'=>"Sfax", 'phone'=>"+216 74 220 555", 'priceRange'=>"€", 'lat'=>34.7406, 'lng'=>10.7603, 'tags'=>"Spicy,Fast,Local", 'image'=>"https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=800&q=80", 'description'=>"The definitive spot for authentic Sfaxian street food and highly spiced snacks in the bustling Medina.", 'openHours'=>"Mon-Sat : 7AM–8PM" ],
  [ 'name'=>"Oasis Cafe", 'cuisine'=>"Tunisian", 'category'=>"brasserie", 'address'=>"Zone Touristique", 'city'=>"Tozeur", 'phone'=>"+216 76 450 111", 'priceRange'=>"€", 'lat'=>33.9185, 'lng'=>8.1336, 'tags'=>"Dates,Palm Trees,Tea", 'image'=>"https://images.unsplash.com/photo-1466978913421-dad2ebd01d17?w=800&q=80", 'description'=>"Set deep in the Tozeur oasis. Enjoy fresh dates, mint tea, and mild curries shaded by palm trees.", 'openHours'=>"Daily : 9AM–11PM" ],
  [ 'name'=>"Dar Djerba", 'cuisine'=>"Seafood", 'category'=>"fruits-de-mer", 'address'=>"Houmt Souk", 'city'=>"Medenine", 'phone'=>"+216 75 650 999", 'priceRange'=>"€€", 'lat'=>33.8750, 'lng'=>10.8580, 'tags'=>"Island,Octopus,Rice", 'image'=>"https://images.unsplash.com/photo-1559339352-11d035aa65de?w=800&q=80", 'description'=>"Experience Djerbian cuisine, famously known for its Djerbian rice and grilled octopus.", 'openHours'=>"Daily : 12PM–10PM" ],
  [ 'name'=>"Kairouan Sweets & Meats", 'cuisine'=>"Tunisian", 'category'=>"street-food", 'address'=>"Avenue de la République", 'city'=>"Kairouan", 'phone'=>"+216 77 220 000", 'priceRange'=>"€", 'lat'=>35.6781, 'lng'=>10.0963, 'tags'=>"Makroudh,Lamb,Traditional", 'image'=>"https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=800&q=80", 'description'=>"Best known for slow-cooked lamb and famous Kairouan Makroudh pastries.", 'openHours'=>"Daily : 8AM–8PM" ],
  [ 'name'=>"Le Kef Belvedere", 'cuisine'=>"Tunisian", 'category'=>"brasserie", 'address'=>"Kasbah area", 'city'=>"Le Kef", 'phone'=>"+216 78 200 444", 'priceRange'=>"€€", 'lat'=>36.1826, 'lng'=>8.7148, 'tags'=>"Mountains,Bread,View", 'image'=>"https://images.unsplash.com/photo-1551218808-94e220e084d2?w=800&q=80", 'description'=>"Perched on a hill near the Kasbah, providing stunning views of the surrounding plains and serving hearty mountain food.", 'openHours'=>"Tue-Sun : 11AM–9PM" ],
  [ 'name'=>"Tataouine Troglodyte", 'cuisine'=>"African", 'category'=>"africaine", 'address'=>"Chenini Village", 'city'=>"Tataouine", 'phone'=>"+216 75 860 111", 'priceRange'=>"€€", 'lat'=>32.9297, 'lng'=>10.4518, 'tags'=>"Berber,Cave,Desert", 'image'=>"https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&q=80", 'description'=>"Dine inside an authentic Berber cave in Chenini. Try the traditional Berber couscous and local olive oil.", 'openHours'=>"Daily : 10AM–6PM" ],
  [ 'name'=>"Sousse Riviera", 'cuisine'=>"Mediterranean", 'category'=>"gastronomique", 'address'=>"Boujaffar Corniche", 'city'=>"Sousse", 'phone'=>"+216 73 222 999", 'priceRange'=>"€€€", 'lat'=>35.8256, 'lng'=>10.6369, 'tags'=>"Luxury,Sea View,Italian-Tunisian", 'image'=>"https://images.unsplash.com/photo-1559339352-11d035aa65de?w=800&q=80", 'description'=>"A fine dining mix of Italian and Tunisian seafood right on the bustling Sousse Corniche.", 'openHours'=>"Daily : 12PM–11:30PM" ],
  [ 'name'=>"Beja Dairy & Grill", 'cuisine'=>"Tunisian", 'category'=>"brasserie", 'address'=>"Avenue Habib Bourguiba", 'city'=>"Béja", 'phone'=>"+216 78 444 222", 'priceRange'=>"€", 'lat'=>36.7256, 'lng'=>9.1817, 'tags'=>"Dairy,Farming,Fresh", 'image'=>"https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=800&q=80", 'description'=>"Located in Tunisia's agricultural heartland. Famous for farm-fresh cheeses, ricotta, and hearty grills.", 'openHours'=>"Daily : 7AM–5PM" ],
  [ 'name'=>"Jendouba Forest Cafe", 'cuisine'=>"Tunisian", 'category'=>"africaine", 'address'=>"Ain Draham Route", 'city'=>"Jendouba", 'phone'=>"+216 78 600 333", 'priceRange'=>"€€", 'lat'=>36.5011, 'lng'=>8.7802, 'tags'=>"Forest,Wild Boar,Cozy", 'image'=>"https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&q=80", 'description'=>"Hidden in the oak forests near Ain Draham. Offers unique local forest specialties.", 'openHours'=>"Daily : 10AM–8PM" ],
  [ 'name'=>"Monastir Marina", 'cuisine'=>"Italian", 'category'=>"italien", 'address'=>"Cap Marina", 'city'=>"Monastir", 'phone'=>"+216 73 460 777", 'priceRange'=>"€€", 'lat'=>35.7770, 'lng'=>10.8261, 'tags'=>"Yachts,Pizza,Sunset", 'image'=>"https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800&q=80", 'description'=>"Pizzeria and Italian dining viewing the beautiful yachts of Monastir marina.", 'openHours'=>"Daily : 12PM–11PM" ],
  [ 'name'=>"Siliana Terroir", 'cuisine'=>"Tunisian", 'category'=>"brasserie", 'address'=>"Downtown", 'city'=>"Siliana", 'phone'=>"+216 77 870 123", 'priceRange'=>"€", 'lat'=>36.0849, 'lng'=>9.3708, 'tags'=>"Rural,Authentic,Olive Oil", 'image'=>"https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=800&q=80", 'description'=>"A rustic eatery focusing on dishes prepared with Siliana's award-winning olive oils.", 'openHours'=>"Mon-Sat : 8AM–4PM" ],
  [ 'name'=>"Gabes Oasis Food", 'cuisine'=>"African", 'category'=>"africaine", 'address'=>"Chenini Oasis", 'city'=>"Gabès", 'phone'=>"+216 75 220 333", 'priceRange'=>"€", 'lat'=>33.8815, 'lng'=>10.0982, 'tags'=>"Pomegranate,Farm,Green", 'image'=>"https://images.unsplash.com/photo-1466978913421-dad2ebd01d17?w=800&q=80", 'description'=>"Enjoy meals heavily featuring local dates and pomegranates within the unique maritime oasis of Gabès.", 'openHours'=>"Daily : 9AM–6PM" ],
  [ 'name'=>"Gafsa Diner", 'cuisine'=>"Tunisian", 'category'=>"street-food", 'address'=>"Avenue Habib Bourguiba", 'city'=>"Gafsa", 'phone'=>"+216 76 222 111", 'priceRange'=>"€", 'lat'=>34.4250, 'lng'=>8.7842, 'tags'=>"Cheap,Fast,Spicy", 'image'=>"https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=800&q=80", 'description'=>"A famous stopover for travelers heading south, offering the best street-style omelettes and Mlawi.", 'openHours'=>"Daily : 24/7" ],
  [ 'name'=>"Kasserine Peaks", 'cuisine'=>"Brasserie", 'category'=>"brasserie", 'address'=>"Chambi Mountain Route", 'city'=>"Kasserine", 'phone'=>"+216 77 400 222", 'priceRange'=>"€€", 'lat'=>35.1676, 'lng'=>8.8365, 'tags'=>"Trails,Grill,Nature", 'image'=>"https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&q=80", 'description'=>"A great post-hike stop offering grilled meats and hearty soups near Mount Chambi.", 'openHours'=>"Tue-Sun : 10AM–7PM" ],
  [ 'name'=>"Kebili Desert Camp", 'cuisine'=>"African", 'category'=>"africaine", 'address'=>"Douz Desert", 'city'=>"Kebili", 'phone'=>"+216 75 490 888", 'priceRange'=>"€€€", 'lat'=>33.7050, 'lng'=>8.9650, 'tags'=>"Sand,Campfire,Under Stars", 'image'=>"https://images.unsplash.com/photo-1551218808-94e220e084d2?w=800&q=80", 'description'=>"Dine beneath the stars in the Sahara. Bread baked in the sand and slow-roasted meats at a luxury camp.", 'openHours'=>"Daily : 6PM–11PM" ]
];

$restaurants = array_merge($restaurants, require __DIR__ . '/extra_restaurants.php');

try {
    $cnx->exec("CREATE TABLE IF NOT EXISTS restaurants (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        cuisine VARCHAR(100),
        category VARCHAR(100),
        address VARCHAR(255),
        city VARCHAR(100),
        phone VARCHAR(50),
        priceRange VARCHAR(10),
        lat FLOAT,
        lng FLOAT,
        tags VARCHAR(255),
        image VARCHAR(500),
        description TEXT,
        openHours VARCHAR(100)
    )");

    $cnx->exec("CREATE TABLE IF NOT EXISTS reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        restaurant_id INT NOT NULL,
        user_id INT,
        author VARCHAR(100),
        rating INT NOT NULL,
        ambiance INT NOT NULL DEFAULT 0,
        cleanliness INT NOT NULL DEFAULT 0,
        quality INT NOT NULL DEFAULT 0,
        service INT NOT NULL DEFAULT 0,
        date DATE NOT NULL,
        text TEXT,
        facture_code VARCHAR(100) DEFAULT NULL,
        INDEX idx_reviews_facture_code (facture_code),
        FOREIGN KEY(restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
    )");

    // Insert
    $stmt = $cnx->prepare("INSERT INTO restaurants (name, cuisine, category, address, city, phone, priceRange, lat, lng, tags, image, description, openHours) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");

    $cnx->exec("TRUNCATE TABLE reviews");
    $cnx->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE restaurants; SET FOREIGN_KEY_CHECKS = 1;");

    foreach($restaurants as $r) {
        $stmt->execute([ $r['name'], $r['cuisine'], $r['category'], $r['address'], $r['city'], $r['phone'], $r['priceRange'], $r['lat'], $r['lng'], $r['tags'], $r['image'], $r['description'], $r['openHours'] ]);
    }
    
    echo "Restaurants imported successfully.\n";
} catch (Exception $e) {
    echo "Error: ". $e->getMessage() ."\n";
}
?>
