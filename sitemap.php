<?php

include_once 'options.php';
$site_url = get_options('url');
$urls = array(
    $site_url,
    $site_url.'about_us',
    $site_url.'contact_us',
    $site_url.'login',
    $site_url.'register',
    $site_url.'profile',
    $site_url.'user',
    $site_url.'links',
    $site_url.'add_link',
);
foreach(all_pages() as $page){
    $urls[] = $site_url.'page/'.$page['id'];
}
header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
foreach ($urls as $url) {
    echo '<url>'."\n";
    echo '<loc>'.$url.'</loc>'."\n";
    echo '<lastmod>'.date("Y-m-d").'</lastmod>'."\n";
    echo '<changefreq>weekly</changefreq>'."\n";
    echo '</url>'."\n";
}
echo '</urlset>';