<!-- google analytic code -->


<!-- google analytic code ends here -->

<!-- following js will activate the menu in left side bar based on url -->
<script type="text/javascript">
    $(document).ready(function () {
        $("#sidebar-menu a").each(function () {
            if (this.href == window.location.href) {
                $(this).addClass("active");
                $(this).parent().addClass("active"); // add active to li of the current link
                $(this).parent().parent().prev().addClass("active"); // add active class to an anchor
                $(this).parent().parent().prev().click(); // click the item to make it drop
            }
        });
    });
</script>

</body>
</html>


<!--<script src="https://js.pusher.com/4.1/pusher.min.js"></script>-->
<!--<script type="text/javascript">-->
<!--    // Enable pusher logging - don't include this in production-->
<!--    Pusher.log = function(message) {-->
<!--        if (window.console && window.console.log) {-->
<!--            window.console.log(message);-->
<!--        }-->
<!--    };-->
<!---->
<!--    //    var pusher = new Pusher('7b98d4665f12dc313bbb');-->
<!--    var pusher = new Pusher('7b98d4665f12dc313bbb', {-->
<!--        cluster: 'ap2',-->
<!--        encrypted: true-->
<!--    });-->
<!--    var channel = pusher.subscribe('my-channel');-->
<!---->
<!--    channel.bind('my_event', function(data) {-->
<!--        document.getElementById('event').innerHTML = data.message;-->
<!--        alert(data.message);-->
<!--    });-->
<!--</script>-->







