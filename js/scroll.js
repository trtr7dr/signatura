<script type="text/javascript">

$(document).ready( function () {
	alert("1");
    $(window).scroll( function () {
        winScroll = $(window).scrollTop();
        winHeight = $(window).height();
        docHeight = $(document).height();
    }

    if((winScroll + winHeight) == docHeight){
        $("#comments").append('tst');
    }

});

</script>