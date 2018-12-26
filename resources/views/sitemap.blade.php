<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>https://www.imcn.vip</loc>
        <lastmod>{{ $add_time }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>1</priority>
    </url>
    @foreach($albums as $vv)
        <url>
            <loc>https://www.imcn.vip/album/detail/{{ $vv }}.html</loc>
            <lastmod>{{ $add_time }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>
