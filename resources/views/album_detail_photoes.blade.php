<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ $title }} · iLegs</title>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport"/>
        <meta content="black" name="apple-mobile-web-app-status-bar-style"/>
        <meta content="telephone=no,email=no,adress=no" name="format-detection"/>
        <link href="{{ $s }}/lightGallery-1.6.11/lightgallery.min.css" rel="stylesheet">
        <script src="{{ $s }}/js/jquery-1.10.2.js"></script>
    </head>
    <body class="home">
        <div class="demo-gallery">
            <ul id="lightgallery" class="list-unstyled row">
                @foreach($photoes as $photo)
                <li class="col-xs-6 col-sm-4 col-md-3" data-responsive="{{ $photo['preview'] }}"  data-src="{{ $photo['original'] }}" data-sub-html="">
                    <a href="javascript:void(0);">
                        <img class="img-responsive" src="{{ $photo['preview'] }}">
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        <script src="{{ $s }}/lightGallery-1.6.11/picturefill.min.js"></script>
        <script src="{{ $s }}/lightGallery-1.6.11/lightgallery-all.min.js"></script>
        <script src="{{ $s }}/lightGallery-1.6.11/jquery.mousewheel.min.js"></script>
        <script type="text/javascript">
          $(document).ready(function(){
            $('#lightgallery').lightGallery();
          });
        </script>
    </body>
</html>
