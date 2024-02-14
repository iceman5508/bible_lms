<!-- My Code Start -->
<div class="video-player-area">
    @if(@$type == 'youtube')
        <!--Youtube Video Frame -->
        <div class="youtubePlayer">
            <iframe class="xdPlayer" src="https://www.youtube.com/embed/{{ @$video_src }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <!--Youtube Video Frame -->
    @elseif(@$type == 'vimeo')
        <!--Vimeo Video Frame -->
        <div class="vimeoPlayer">
            <iframe class="xdPlayer" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" allow="autoplay" frameborder="0" src="{{ @$video_src }}"></iframe>
        </div>
        <!--Vimeo Video Frame -->
    @elseif(@$type == 'video')
        <!--Video/mp4 Video Frame -->
        <video class="videoPlayer " controls controlsList="nodownload">
            <source class="xdPlayer lectureVideo" src="{{ getVideoFile(@$video_src) }}" type="video/mp4">
        </video>
        <!--Video/mp4 Video Frame -->
    @endif
</div>
<!-- My Code End -->
