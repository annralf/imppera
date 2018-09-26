<?php
include'/var/www/html/enkargo/config/meli_bench.php';

$bench = new Benchmark(1);
$bench->set_info_sellers_daily_sales();