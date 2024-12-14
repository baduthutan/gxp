<div id="slick_slider" class="shadow" style="margin-top: 0px;">
    @foreach ($banners as $banner)
        <div>
            <img src="{{ $banner->picture }}" alt="" loading="eager"
                onClick="window.location.replace('{{ $banner->url }}')" class="img-fluid" style="max-height: 500px">
        </div>
    @endforeach
</div>
