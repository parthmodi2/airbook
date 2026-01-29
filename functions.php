<?php
// functions.php
require_once 'db.php';

function search_flights($origin, $destination, $date) {
    global $pdo;
    $params = [];
    $sql = "SELECT * FROM flights WHERE 1=1";

    if ($origin) {
        $sql .= " AND origin LIKE :origin";
        $params[':origin'] = "%$origin%";
    }
    if ($destination) {
        $sql .= " AND destination LIKE :destination";
        $params[':destination'] = "%$destination%";
    }
    if ($date) {
        $sql .= " AND DATE(depart_time) = :date";
        $params[':date'] = $date;
    }
    $sql .= " ORDER BY depart_time ASC LIMIT 200";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($rows) > 0) {
        return $rows;
    }

    return generate_flights_on_the_fly($origin, $destination, $date);
}

function generate_flights_on_the_fly($origin, $destination, $date) {
    $cities = [
        'Mumbai','Delhi','Bengaluru','Chennai','Kolkata','Hyderabad','Pune','Ahmedabad','Goa','Jaipur'
    ];

    if (!$origin) $origin = $cities[array_rand($cities)];
    if (!$destination) {
        do {
            $destination = $cities[array_rand($cities)];
        } while (strtolower($destination) == strtolower($origin));
    }

    $results = [];
    $baseDate = $date ? new DateTime($date) : new DateTime();
    for ($i=0; $i<6; $i++) {
        $dep = clone $baseDate;
        $hour = (5 + ($i*3)) % 24;
        $minute = [0,15,30,45][array_rand([0,1,2,3])];
        $dep->setTime($hour, $minute);
        $arr = clone $dep;
        $duration = 45 + rand(30, 240);
        $arr->add(new DateInterval('PT' . $duration . 'M'));
        $price = round(1500 + ($duration * 10) + rand(-300, 700), 2);
        $flight_code = strtoupper(substr($origin,0,2) . rand(100,999));
        $results[] = [
            'id' => 0,
            'flight_code' => $flight_code,
            'origin' => $origin,
            'destination' => $destination,
            'depart_time' => $dep->format('Y-m-d H:i:s'),
            'arrive_time' => $arr->format('Y-m-d H:i:s'),
            'duration_minutes' => $duration,
            'price' => $price,
            'seats_total' => 180,
            'seats_booked' => 0
        ];
    }
    return $results;
}

function is_admin() {
    return (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin');
}
?>