<?php
/*
Plugin Name: Filenames to latin
Plugin URI: http://wordpress.org/extend/plugins/filenames-to-latin/
Description: Sanitize cyrillic, german, french, polish, spanish, hungarian, czech and other filenames to latin during upload
Version: 1.6
Author: webvitaly
Author URI: http://profiles.wordpress.org/webvitaly/
License: GPLv2 or later
*/


if( ! function_exists( 'filenames_to_latin_unqprfx' ) ) :
	function filenames_to_latin_unqprfx( $filename ) {
		$original_chars = array(
			'/А/','/Б/','/В/','/Г/', // cyrillic alphabet
			'/Д/','/Е/','/Ж/','/З/','/И/',
			'/Й/','/К/','/Л/','/М/','/Н/',
			'/О/','/П/','/Р/','/С/','/Т/',
			'/У/','/Ф/','/Х/','/Ц/','/Ч/',
			'/Ш/','/Щ/','/Ь/','/Ю/','/Я/',
			'/а/','/б/','/в/','/г/','/д/','/е/','/ж/',
			'/з/','/и/','/й/','/к/','/л/',
			'/м/','/н/','/о/','/п/','/р/',
			'/с/','/т/','/у/','/ф/','/х/',
			'/ц/','/ч/','/ш/','/щ/',
			'/ь/','/ю/','/я/',

			'/Ґ/','/ґ/','/Є/','/є/','/І/','/і/','/Ї/','/ї/', // ukrainian
			'/Ё/','/ё/','/Ы/','/ы/','/Ъ/','/ъ/','/Э/','/э/', // russian
			'/Ў/','/ў/', // belorussian
			'/Ä/','/ä/','/Ö/','/ö/','/Ü/','/ü/','/ß/', // german
			'/Ą/','/ą/','/Ć/','/ć/','/Ę/','/ę/','/Ł/','/ł/','/Ń/','/ń/','/Ó/','/ó/','/Ś/','/ś/','/Ź/','/ź/','/Ż/','/ż/', // polish (new unique letters)
			'/Ő/','/ő/','/Ű/','/ű/', // hungarian
			'/ě/','/š/','/č/','/ř/','/ž/','/ý/','/á/','/é/','/ď/','/ť/','/ň/','/ú/','/ů/', // czech
			'/Ě/','/Š/','/Č/','/Ř/','/Ž/','/Ý/','/Á/','/É/','/Ď/','/Ť/','/Ň/','/Ú/','/Ů/',

			'/À/','/Á/','/Â/','/Ã/','/Å/','/Æ/','/Ç/','/È/','/É/','/Ê/','/Ë/','/Ì/','/Í/','/Î/','/Ï/','/Ð/','/Ñ/','/Ò/','/Ô/','/Õ/','/×/','/Ø/','/Ù/','/Ú/','/Û/','/Ý/','/Þ/', // extra all (http://www.atm.ox.ac.uk/user/iwi/charmap.html)
			'/à/','/á/','/â/','/ã/','/å/','/æ/','/ç/','/è/','/é/','/ê/','/ë/','/ì/','/í/','/î/','/ï/','/ð/','/ñ/','/ò/','/ô/','/õ/','/×/','/ø/','/ù/','/ú/','/û/','/ý/','/þ/','/ÿ/','/Ÿ/',

			'/№/','/“/','/”/','/«/','/»/','/„/','/@/','/%/', // other
			'/‘/','/’/','/`/','/´/','/º/','/ª/'
		);
		$sanitized_chars = array(
			'a','b','v','h', //cyrillic alphabet
			'd','e','zh','z','y',
			'j','k','l','m','n',
			'o','p','r','s','t',
			'u','f','h','c','ch',
			'sh','shh','','ju','ja',
			'a','b','v','h','d','e','zh',
			'z','y','j','k','l',
			'm','n','o','p','r',
			's','t','u','f','h',
			'c','ch','sh','sch',
			'','ju','ja',

			'g','g','je','je','i','i','ji','ji', // ukrainian
			'jo','jo','y','y','','','ye','ye', // russian
			'u','u', // belorussian
			'ae','ae','oe','oe','ue','ue','ss', // german
			'a','a','c','c','e','e','l','l','n','n','o','o','s','s','z','z','z','z', // polish
			'o','o','u','u', // hungarian
			'e','s','c','r','z','y','a','e','d','t','n','u','u',  //czech
			'e','s','c','r','z','y','a','e','d','t','n','u','u',

			'a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','d','n','o','o','o','x','o','u','u','u','y','p', // extra all
			'a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','d','n','o','o','o','x','o','u','u','u','y','p','y','y',

			'','','','','','','','', // other
			'','','','','o','a'
		);

		$friendly_filename = preg_replace( $original_chars, $sanitized_chars, $filename ); // replace original chaars in filename with friendly chars

		return strtolower( $friendly_filename ); // filename to lowercase
	}
	add_filter( 'sanitize_file_name', 'filenames_to_latin_unqprfx', 10 );
endif;


if( ! function_exists( 'filenames_to_latin_unqprfx_plugin_meta' ) ) :
	function filenames_to_latin_unqprfx_plugin_meta( $links, $file ) { // add 'Plugin page' and 'Donate' links to plugin meta row
		if ( strpos( $file, 'filenames-to-latin.php' ) !== false ) {
			$links = array_merge( $links, array( '<a href="http://web-profile.com.ua/wordpress/plugins/filenames-to-latin/" title="Plugin page">' . __('Filenames to latin') . '</a>' ) );
			$links = array_merge( $links, array( '<a href="http://web-profile.com.ua/donate/" title="Support the development">' . __('Donate') . '</a>' ) );
		}
		return $links;
	}
	add_filter( 'plugin_row_meta', 'filenames_to_latin_unqprfx_plugin_meta', 10, 2 );
endif;
