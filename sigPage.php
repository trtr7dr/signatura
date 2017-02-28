<?php 
	/*
	Template Name: SigPage
	*/

	get_header(); ?>

<section class="content">
	
	<?php //get_template_part('inc/page-title'); 
		
		
		wp_enqueue_script('jquery3.1.1', get_template_directory_uri() . '/js/glo/jquery-3.1.1.min.js');
		wp_enqueue_script('color-thief', get_template_directory_uri() . '/js/glo/color-thief.js');
		wp_enqueue_script('scriptjava', get_template_directory_uri() . '/js/glo/scriptjava.js');
		//wp_enqueue_script('scriptjava', get_template_directory_uri() . '/js/glo/color-thief.min.js');
		//wp_enqueue_script('sigScript', get_template_directory_uri() . '/js/glo/mustache.js');
		//wp_enqueue_script('sigScript', get_template_directory_uri() . '/js/glo/demo.js');
		wp_enqueue_script('sigScript', get_template_directory_uri() . '/js/glo/sigScript.js');
		wp_enqueue_style('skeleton', get_template_directory_uri() . '/skeleton/skeleton.css');
		wp_enqueue_style('normalize', get_template_directory_uri() . '/skeleton/normalize.css');
		
		get_template_part('/js/glo/ajax/connect');
		get_template_part('/js/glo/ajax/function');
		
		  
		  
		
	?>
	
	<div class="pad group">
		
		<?php while ( have_posts() ): the_post(); ?>
		
			<article <?php //post_class('group'); ?>>
				
				<?php //get_template_part('inc/page-image'); ?>
				
				<div class="entry themeform sigAdmin">
					<div class="four columns sigCol" >
						
						Добавить картины
						
						<select id="haveName">
						<option>Новый художник</option>
						<?php
							get_template_part('/js/glo/ajax/getArtist');
						?>
						</select>
						
						<input type="text" id="name" value="" placeholder="Имя нового художника"/>						
						
						<br>
						<select id="dayTime">
							<option>не выбрано</option>
							<option>день</option>
							<option>ночь</option>
						</select>
						<label for="dayTime">Выберите время суток</label>

						
						<input type="file" id="files" name="files[]" multiple />
						
						
						
						<output id="list"></output>
						<div id="miniRes"></div>
						
					
					</div>
					
					<div class="four columns" >
						
						
						Добавить жанр<br>
						
						<select id="ganre">
						<option>Новый жанр</option>
						<?php
							get_template_part('/js/glo/ajax/getGanre');
						?>
						</select>
						<input type="text" id="newGanre" value="" placeholder="Новый жанр"/>	<br>
						
						Художнику<br>
						<select id="nameGanre">
						<?php
							get_template_part('/js/glo/ajax/getArtist');
						?>
						</select>
						<div class="button" onclick="ganreTeach();">Добавить отношение</div>
						<div class="button" onclick="vectorTeach();">Определить вектора</div>
						<br><br>
						
						
					</div>
						
					<div class="four columns" >
						Удалить художника
						<select id="delName">
						<?php
							get_template_part('/js/glo/ajax/getArtist');
						?>
						</select>
						
						<input type="text" id="nameTest" value="" placeholder="Ввести имя заново"/>
						<div class="button" onclick="deleteName();">Удалить</div>
						
						<br>
						Удалить все данные
						<input type="text" id="passDel" value="" placeholder="Пароль"/>
						<div class="button" onclick="deleteAll();">Удалить</div>
						
					</div>
					<div class="twelve columns sigAdminRes" id="result">
					
						Результаты
					
					</div>
				
				</div><!--/.entry-->
				
			</article>
			
			
			
		<?php endwhile; ?>
		
	</div><!--/.pad-->
	
</section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>