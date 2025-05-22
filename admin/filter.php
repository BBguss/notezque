<?php
function kondisiFilter($status)
{
    $kondisi = "username != 'admin'";

    if ($status == 'aktif') {
        $kondisi .= " AND aktivitas_terakhir > DATE_SUB(NOW(), INTERVAL 1 DAY)";
    } elseif ($status == 'tidak_aktif') {
        $kondisi .= " AND (aktivitas_terakhir IS NULL OR aktivitas_terakhir <= DATE_SUB(NOW(), INTERVAL 1 DAY))";
    }

    return $kondisi;
}

function kondisiurutan($sort_by) {
    $order_by = "aktivitas_terakhir DESC";

    if ($sort_by == 'created_at_asc') {
        $order_by = "created_at ASC";
    } elseif ($sort_by == 'created_at_desc') {
        $order_by = "created_at DESC";
    }

    return $order_by;
}