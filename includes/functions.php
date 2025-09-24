<?php

function timeAgo($datetime) {
    $timezone = new DateTimeZone("Asia/Jakarta");
    $now = new DateTime('now', $timezone);
    $date = new DateTime($datetime, $timezone);
    $diff = $now->diff($date);

    if ($diff->y > 0) {
        return $diff->y . " tahun yang lalu";
    } elseif ($diff->m > 0) {
        return $diff->m . " bulan yang lalu";
    } elseif ($diff->d > 0) {
        return $diff->d . " hari yang lalu";
    } elseif ($diff->h > 0) {
        return $diff->h . " jam yang lalu";
    } elseif ($diff->i > 0) {
        return $diff->i . " menit yang lalu";
    } else {
        return "baru saja";
    }
}