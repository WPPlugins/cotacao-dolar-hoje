<?php
/*

	Plugin Name: Cotação Dólar
	Plugin URI: http://www.cotacaocolarhoje.com
	Description: Tenha a cotação do dólar em seu site - atualizado diariamente direto do site do Banco Central.
	Version: 2.1
	Author: Fernando Becker
	Author URI: http://www.fernandobecker.com.br
	License: GPLv2
	
	Copyright 2015 Fernando Becker <febeckers@gmail.com>
	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 3 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	
	GNU General Public License for more details.
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
	MA 02110-1301, USA.

*/

class cotaocaDolar extends WP_Widget{

	public $valor, $autor, $site;

	public function __construct() {
		
		$widget_options = array('description' => 'Cotação do dólar em tempo real no seu site');
		parent::__construct('cotacaodolar', 'Cotação Dólar Hoje', $widget_options);
	
	}

	public function widget($args, $instancia) {		
		
		self::getjson();
		
		if( !empty( $this->valor ) ):
		
			echo $args['before_widget'];
		
			?>
            <div id="cotacaodolar">
				<?php
					echo $args['before_title'] . apply_filters( 'widget_title', 'Cota&ccedil;&atilde;o do d&oacute;lar' ). $args['after_title'];
				?>
				<strong>U$1,00 = R$<?php echo $this->valor?></strong>
				<div>
                	Valor para <?php echo date('d/m/Y')?> &agrave;s <?php echo date('H:00')?> pego em 
                    <br/><a href="<?php echo $this->site?>"><?php echo $this->autor?></a>
                </div>
			</div>
            <?
			
			echo $args['after_widget'];
		
		endif;
		
	}
	
	private function getjson(){
		
		$cURL = curl_init( 'http://www.cotacaodolarhoje.com/webservice/' );
	
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		
		$dados = array( 'apikey' 	=> 'kheggmgiaskuo28l0m91m1p527', 
						'site'		=> $_SERVER['HTTP_HOST'] );
		
		curl_setopt($cURL, CURLOPT_POST, true);
		curl_setopt($cURL, CURLOPT_POSTFIELDS, http_build_query( $dados ) );
		$resultado = curl_exec($cURL);
		curl_close($cURL);
		
		$json = explode( "|", $resultado );
		
		if( $json[0]!="" ):
		
			$this->valor 	= $json[0];
			$this->site 	= $json[1];
			$this->autor 	= $json[2];		
		
		else:
		
			echo 'Erro na API, solicite correção a febeckers@gmail.com';
		
		endif;
	
	}
	
	/**
	 * Formulário para os dados do widget (exibido no painel de controle)
	 *
	 * @param array $instancia Instância do widget
	 */
		public function form($instancia) {
		
			echo '<p>Você pode customizar o seu widget no CSS do próprio plugin</p>';
			
		}
	
		public function update($new_instance, $old_instance){}

	}
	
	wp_enqueue_style( 'prefix-style', plugins_url('css/cotacaodolar.css', __FILE__) );
	add_action('widgets_init', create_function('', 'return register_widget("cotaocaDolar");'));

?>