 //
 fetch("ajax-handler.php", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "X-CSRF-Token": "<?= CSRF::getInstance()->getTokenHeader(); ?>"
    },
    body: JSON.stringify({ id: 123 })
})
.then(res => res.json())
.then(data => console.log(data));

ajax doğrulama kontrölü
 if (!$csrf->validateAjaxRequest()) {
    http_response_code(403);
    echo json_encode(['error' => 'Geçersiz CSRF token!']);
    exit;
}



//FORM için csrf

echo $csrf->getTokenInputField();

 $csrf = CSRF::getInstance();

if (!$csrf->validateFormRequest()) {
    die("Geçersiz CSRF token!");
}

$price = $request->get('price', 0.0, 'float');
Request sınıfındaki get metodu üç parametre alıyor:

1️⃣ 'price' → $key
Ne için: GET parametresinin adı.

Örnek URL: https://site.com/?price=12.99

Burada $_GET['price'] değerini alacak.

2️⃣ 0.0 → $default
Ne için: Eğer GET parametresi boş veya gelmemişse kullanılacak varsayılan değer.

Örnek: Eğer URL: https://site.com/ ise $_GET['price'] yok → $price 0.0 olur.

3️⃣ 'float' → $type
Ne için: Alınan değerin hangi tipe dönüştürüleceğini belirler.

'float' yazıldığı için:

$_GET['price'] değeri float’a çevrilir ((float) ile)

Hatalı veya boş değerler default $default kullanır.