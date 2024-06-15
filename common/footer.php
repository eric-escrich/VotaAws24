<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<section id="promotional-section">
    <img src="../imgs/vota_banner.gif" alt="Vota! Ya!" title="Vota! Ya!" width="970" height="250">
</section>
<footer>
    <div class="authors">
        <div class="continfo">
            <p class="author">Eric</p>
            <p class="mail">eescrichalmagro.cf@iesesteverradas.cat</p>
        </div>
        <div class="continfo">
            <p class="author">Pol</p>
            <p class="mail">pcortesgarcia.cf@iesesteveterradas.cat</p>
        </div>
        <div class="continfo">
            <p class="author">Renato</p>
            <p class="mail">ffloresgarcia.cf@iesesteverradas.cat</p>
        </div>
    </div>

    <div class="links">
        <div>
            &copy <a href="https://www.iesesteveterradas.cat/" target="_blank">IES Esteve Terradas iIlla</a> | <a
                href="https://github.com/Barney2500/VotaAws24" target="_blank">GitHub Grupal</a>
        </div>
    </div>
</footer>
<script>
    $(document).ready(function () {
        $(window).resize(function () {
            // Verifica si el ancho de la ventana es menor o igual a 1000px
            if ($(window).width() <= 1000) {
                $('footer .continfo').not(this).children('p:last-child').hide();
                $('footer .continfo').off('click').on('click', function () {
                    $('footer .continfo').not(this).children('p:last-child').hide();
                    $(this).children('p:last-child').toggle();
                });
            } else {
                $('footer .continfo').off('click');
                $('footer .continfo').not(this).children('p:last-child').show();
            }
        }).trigger('resize');
    });
</script>