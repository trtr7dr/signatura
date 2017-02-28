<?php 
	/*
	Template Name: SignaturaUser
	*/

	get_header(); ?>
 
<section class="content signatura">
	
	<?php 
		//get_template_part('inc/page-title'); 
		
		wp_enqueue_script('jquery3.1.1', get_template_directory_uri() . '/js/glo/jquery-3.1.1.min.js');
		wp_enqueue_script('color-thief', get_template_directory_uri() . '/js/glo/color-thief.js');
		wp_enqueue_script('scriptjava', get_template_directory_uri() . '/js/glo/scriptjava.js');
		//wp_enqueue_script('scriptjava', get_template_directory_uri() . '/js/glo/color-thief.min.js');
		//wp_enqueue_script('sigScript', get_template_directory_uri() . '/js/glo/mustache.js');
		//wp_enqueue_script('sigScript', get_template_directory_uri() . '/js/glo/demo.js');
		wp_enqueue_script('sigScript', get_template_directory_uri() . '/js/glo/signa.js');
		wp_enqueue_script('facedetection', get_template_directory_uri() . '/js/glo/dist/jquery.facedetection.js');
		wp_enqueue_script('facedetection', get_template_directory_uri() . '/js/glo/jquery.lazyload/jquery.lazyload.js');
		wp_enqueue_script('facedetection', get_template_directory_uri() . '/js/glo/jquery.lazyload/jquery.lazyload.use.js');
		
	?>
	
	<div class="pad group">
		
		<?php while ( have_posts() ): the_post(); ?>
		
			<article <?php post_class('group'); ?>>
				
				<?php get_template_part('inc/page-image'); ?>
				
				<div class="entry themeform">
					
					
					
					
					<div class="file-upload">
					     <label>
					<input type="file" name="file" class="inputfile" id="files"  />
					<span class="choseFile">Выберите файл</span>
					    	</label>
					</div>

					<output id="list"></output>
					<div id="miniRes"></div>
					
					<div id="resultFace"></div>
					<div id="result" class="resSig"> <span style="text-align: center">Добавьте изображение PNG, JPG или JPEG, чтобы получить его цветовой анализ.</span></div>

					<? the_content(); ?>

					<div class="clear spaceComment"></div>
					
					<div class="adsSig">
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- Сигнатура -->
					<ins class="adsbygoogle"
					     style="display:block"
					     data-ad-client="ca-pub-5937727939786722"
					     data-ad-slot="5254050091"
					     data-ad-format="auto"></ins>
					<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
					</script>
					</div>
									
				
				</div><!--/.entry-->
				
			</article>
			
			<?php if ( ot_get_option('page-comments') == 'on' ) { comments_template('comments.php',true); } ?>
			
		<?php endwhile; ?>
		
	</div><!--/.pad-->
	
</section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>