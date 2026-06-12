<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() { 
    $(".alerta-sistema").fadeIn("slow"); 
    setTimeout(function() { $(".alerta-sistema").fadeOut("slow"); }, 3000); 
    $(".input-preview-js").on("change", function() {
        var container = $(this).closest("form").find(".container-preview-js");
        container.html(""); 
        if (this.files) {
            Array.from(this.files).forEach(file => {
                if (!file.type.startsWith("image/")) return;
                var reader = new FileReader();
                reader.onload = function(e) {
                    container.append('<div class="position-relative"><img src="' + e.target.result + '" class="rounded" style="width:100px; height:80px; object-fit:cover; border:1px solid #ddd;"></div>');
                }
                reader.readAsDataURL(file);
            });
        }
    });
});
</script>
</body>
</html>