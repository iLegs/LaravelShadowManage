<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>https://www.imcn.vip</loc>
        <lastmod>{{ $add_time }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>1</priority>
    </url>
    @foreach($libs as $lib)
        <url>
            <loc>https://www.imcn.vip/lib/{{ $lib }}/list.html</loc>
            <lastmod>{{ $add_time }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach
    @foreach($albums as $vv)
        <url>
            <loc>https://www.imcn.vip/album/detail/{{ $vv }}.html</loc>
            <lastmod>{{ $add_time }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
    @foreach($tags as $tag)
        <url>
            <loc>https://www.imcn.vip/tag/{{ $tag }}.html</loc>
            <lastmod>{{ $add_time }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach
    @foreach($mdls as $mdl)
        <url>
            <loc>https://www.imcn.vip/model/{{ $mdl }}.html</loc>
            <lastmod>{{ $add_time }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach
</urlset>
