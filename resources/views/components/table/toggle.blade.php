{{-- x-tags: table.toggle --}}
<a class="link-secondary" href="javascript:toggle();" type="button">
    <i class="bi bi-toggle-off" id="toggle"></i>
</a>

@pushOnce('scripts')
    <script>
        console.log('init toggle');

        function toggle() {
            console.log('toggle');
            let checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(box => {
                box.checked = box.checked ? false : true;
            });


            var toggle = document.getElementById("toggle");
            var toggleClass = toggle.className;
            // console.log(toggleClass);
            if (toggleClass == "bi bi-toggle-off") {
                // console.log('1');
                toggle.className = "bi bi-toggle-on";
            } else {
                // console.log('2');
                toggle.className = "bi bi-toggle-off";
            }

        }
    </script>
@endPushOnce
