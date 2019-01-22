<!DOCTYPE html>

<html>








<head>


	<title></title>

	<meta charset="utf-8">


	<style>


		body {
			margin: 0px;
			padding: 10px;
			font-family: palatino;
			font-size: 14px;
			background: beige;
		}
		noscript {
			outline: 40px solid rgba(245,215,0,1);          /* instead of padding */
			margin: auto;
			position: absolute;
			top: 50%;                                       /* moves down half of visible screen height */
			transform: translateY(-50%);                    /* shifts up half of own height */
			left: 50%;                                      /* moves right half of visible screen width */
			transform: translateX(-50%);                    /* shifts left half of own width */
			background-color: rgba(245,215,0,1);
			box-shadow: 1px 1px 15px 42px rgba(0,0,0,0.5);
		}
		noscript::before {
			content: "This site is thirsty! Give it some JS...";
		}
		a {
			text-decoration: none;
			color: rgb(58,102,87);
		}
		a:hover {
			color: rgb(115,115,115);
		}


		div {
			margin: 5px;
			padding: 5px;
		}


		div#out table.out {
			text-align: center;
		}
		div#out table.out th,
		div#out table.out td {
			border: 1px solid gray;
			padding: 0px 20px;
		}


	</style>


</head>
















<body>




	<noscript></noscript>


	<div id="out">
		
		<?php

			error_reporting(E_ERROR 
				// | E_WARNING | E_PARSE | E_NOTICE  // Uncomment for debugging
			);



			function trimNewLines($string) {
				return str_replace("\012", '', $string);
			}



			function trimSpaces($string) {

				$trimmedContent = '';
				$added = 0;

				$splitted = explode( ' ', $string );

				for ($c=0; $c<sizeof($splitted); ++$c) {

					// For those words that are actual words, not
					// spaces:
					if( ord($splitted[$c])!=0 ) {

						// For the first word, add the word as is - without
						// any whitespace.
						if ($added==0) {
							$trimmedContent .= $splitted[$c];
							++$added;
						}

						// For all other words, add a whitespace before
						// the word.
						else {
							$trimmedContent .= ' ' . $splitted[$c];
							++$added;
						}

					}

				} // Loop ends: One cell content has been trimmed.

				// Create a string from the trimmed words and put it in the
				// original string.
				$string = $trimmedContent;

				return $string;

			}



			// A word is passed as argument and is checked to determine if
			// it is a language name. The first letter of the word is checked
			// first and the word is then accordingly matched against some
			// language names.
			function isLanguage($word) {

				$isLang = false;

				// Note for future:
				// Language names that have whitespaces in between need to be
				// be checked/matched properly. (Currently the content of the
				// language section is split using a whitespace, which does
				// not cover these cases.)
				$languages = array(
					array( "Abkhazian", "Afar", "Afrikaans", "Albanian", "Amharic", "Arabic", "Armenian", "Assamese", "Azerbaijani" ),
					array( "Baatonum", "Balinese", "Bambara", "Bantu(Other)", "Basa", "Basque", "Belarusian", "Bemba", "Bengali", "Berber (Other)", "Bhojpuri (& Tharu)", "Bhojpuri (&amp; Tharu)", "Bhojpuri (& Tharu)", "Bini", "Bislama", "Bosnian", "Bulgarian", "Burmese" ),
					array( "Catalan", "Cebuano (Bisayan)", "Chin", "Chinese", "Creoles & Pidgins (French-based Other)", "Creoles &amp; Pidgins (French-based Other)", "Croatian", "Czech" ),
					array( "Dagbani", "Danish", "Dari", "Dinka", "Dutch" ),
					array( "Esperanto", "Estonian", "Ewe" ),
					array( "Fang", "Farsi (Persian)", "Fijian", "Finnish", "French", "Fulah", "Fulani" ),
					array( "Gaelic", "Galician", "Ganda", "Georgian", "German", "Gilbertese", "Greek", "Guarani", "Gujarati" ),
					array( "Haitian-Creole", "Hausa", "Hebrew", "Hiligaynon", "Hindi", "Hmong", "Hungarian" ),
					array( "Icelandic", "Igbo", "Iloko", "Indonesian", "Irish", "Italian", "Izon" ),
					array( "Japanese", "Javanese" ),
					array( "K'iche'", "Kachin", "Kalenjin ", "Kamba", "Kannada", "Kanuri", "Kara-Kalpak", "Karen", "Kazakh", "Khasi", "Khmer (Central)", "Kikuyu", "Kinyarwanda", "Kirghiz", "Kongo", "Konkani", "Korean", "Krio", "Kurdish" ),
					array( "Lao", "Latin", "Latvian", "Lingala", "Lithuanian", "Lozi", "Luo (Kenya,Tanzania)", "Lushai (Mizo)" ),
					array( "Maay Maay", "Macedonian", "Malagasy", "Malay", "Malayalam", "Maltese", "Manipuri", "Marathi", "Marshallese", "Masai", "Moldavian", "Mongolian" ),
					array( "NdebeleNorth", "Nepali", "Nigerian", "Nilo-Saharan(Other)", "Norwegian", "Norwegian (Bokmal)", "Norwegian (Nynorsk)", "Nyanja" ),
					array( "Oriya", "Oromo" ),
					array( "Panjabi", "Papiamento", "Pashto (Pushto)", "Persian (Farsi)", "Polish", "Portuguese" ),
					array( "Quechua" ),
					array( "Romanian", "Rundi", "Russian" ),
					array( "Sanskrit", "Serbian", "Serbo-Croat", "Shona", "Simple English", "Sindhi", "Sinhala (Sinhalese)", "Slovak", "Slovenian", "Somali", "Songhai", "SothoNorthern", "Southern Sotho / Sesotho", "Spanish", "Sundanese", "Swahili", "Swedish", "Sylheti" ),
					array( "Tagalog", "Tajik", "Tamil", "Telugu", "Tetum", "Thai", "Tibetan", "Tigre", "Tigrinya", "Timne", "Tok Pisin", "Tonga (Nya)", "Tsonga", "Tswana", "Tumbuka", "Turkish", "Turkmen" ),
					array( "Ukrainian", "Urdu", "Uzbek" ),
					array( "Vietnamese" ),
					array( "Waray", "Wolof" ),
					array( "Xhosa" ),
					array( "Yao", "Yoruba" ),
					array( "Zulu" ),
				);

				// Check the first letter first and then match against language
				// names from arrays sorted alphabetically (indices indicating
				// corresponding letters in the English alphabet).
				switch ($word[0]) {
					case 'A':
						$isLang = ( in_array($word, $languages[0]) )  ? true : false;
						break;
					case 'B':
						$isLang = ( in_array($word, $languages[1]) )  ? true : false;
						break;
					case 'C':
						$isLang = ( in_array($word, $languages[2]) )  ? true : false;
						break;
					case 'D':
						$isLang = ( in_array($word, $languages[3]) )  ? true : false;
						break;
					case 'E':
						$isLang = ( in_array($word, $languages[4]) )  ? true : false;
						break;
					case 'F':
						$isLang = ( in_array($word, $languages[5]) )  ? true : false;
						break;
					case 'G':
						$isLang = ( in_array($word, $languages[6]) )  ? true : false;
						break;
					case 'H':
						$isLang = ( in_array($word, $languages[7]) )  ? true : false;
						break;
					case 'I':
						$isLang = ( in_array($word, $languages[8]) )  ? true : false;
						break;
					case 'J':
						$isLang = ( in_array($word, $languages[9]) )  ? true : false;
						break;
					case 'K':
						$isLang = ( in_array($word, $languages[10]) ) ? true : false;
						break;
					case 'L':
						$isLang = ( in_array($word, $languages[11]) ) ? true : false;
						break;
					case 'M':
						$isLang = ( in_array($word, $languages[12]) ) ? true : false;
						break;
					case 'N':
						$isLang = ( in_array($word, $languages[13]) ) ? true : false;
						break;
					case 'O':
						$isLang = ( in_array($word, $languages[14]) ) ? true : false;
						break;
					case 'P':
						$isLang = ( in_array($word, $languages[15]) ) ? true : false;
						break;
					case 'Q':
						$isLang = ( in_array($word, $languages[16]) ) ? true : false;
						break;
					case 'R':
						$isLang = ( in_array($word, $languages[17]) ) ? true : false;
						break;
					case 'S':
						$isLang = ( in_array($word, $languages[18]) ) ? true : false;
						break;
					case 'T':
						$isLang = ( in_array($word, $languages[19]) ) ? true : false;
						break;
					case 'U':
						$isLang = ( in_array($word, $languages[20]) ) ? true : false;
						break;
					case 'V':
						$isLang = ( in_array($word, $languages[21]) ) ? true : false;
						break;
					case 'W':
						$isLang = ( in_array($word, $languages[22]) ) ? true : false;
						break;
					case 'X':
						$isLang = ( in_array($word, $languages[23]) ) ? true : false;
						break;
					case 'Y':
						$isLang = ( in_array($word, $languages[24]) ) ? true : false;
						break;
					case 'Z':
						$isLang = ( in_array($word, $languages[25]) ) ? true : false;
						break;
					default:
						$isLang = false;
						break;
				}

				return $isLang;

			}



			// The entire content of the languages section is passed and
			// a line break is inserted after each language pair (e.g.,
			// "English to Esperanto", "Latin to Gaelic", etc.) and after
			// each set of specializations.
			function separateLanguagePairs($langContent) {

				$words = explode(' ', $langContent);

				// The first three words can be skipped, because regardless
				// of whether they are the only words in the entire content
				// or not, they make up a language pair. If a further pair or
				// a specialization set exists after this pair, a line break
				// will be entered BEFORE them (i.e., AFTER this pair).
				for ($c=3; $c<sizeof($words); ++$c) {

					// If current word is a language name and is followed by
					// "to", it is considered as the starting of a language
					// pair and a line break is inserted before it (i.e., AFTER
					// the preceding language pair or specialization set), and
					// the word counter skips this pair.
					if ( isLanguage($words[$c]) && $words[$c+1]=="to" ) {
						$words[$c] = '<br>' . $words[$c];
						$c += 2;
					}

					// If current word is "Specializes", one more word ("in:")
					// is skipped and then checking continues. (The reason
					// behind skipping only one word is the possibility of
					// no specialization value (i.e., nothing after the "in:"),
					// due to some error.)
					else if ( $words[$c]=="Specializes" ) {
						$words[$c] = '<br>' . $words[$c];
						$c += 1;
					}

				}

				return implode(' ', $words);

			}





			$urlBase = 'https://twb.translationcenter.org';

			// Profile links (added manually)
			$url = array(
				$urlBase . '/workspace/accounts/view/id/823',
				$urlBase . '/workspace/accounts/view/id/661',
				$urlBase . '/workspace/accounts/view/id/8066',
				$urlBase . '/workspace/accounts/view/id/11873',
				$urlBase . '/workspace/accounts/view/id/23318',
				$urlBase . '/workspace/accounts/view/id/12064',
				$urlBase . '/workspace/accounts/view/id/46916',
				$urlBase . '/workspace/accounts/view/id/6439',
				$urlBase . '/workspace/accounts/view/id/11476',
				$urlBase . '/workspace/accounts/view/id/832',
				$urlBase . '/workspace/accounts/view/id/12727',
				$urlBase . '/workspace/accounts/view/id/5788'
			);



			$testUrl = $urlBase . '/workspace/dashboard';

			$handle = curl_init();
			curl_setopt($handle, CURLOPT_URL, $testUrl);
			curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

			$target = curl_exec($handle);

			curl_close($handle);



			if ($target==false) {
				echo "Error: Possible network connectivity or access problem!";
			}

			else {


				$headings  = array( 
					'Username', 
					'Area / Contact', 
					'Languages', 
					'Tasks completed', 
					'Words translated'
				);

				$numColumn = sizeof($headings);
				
				echo '<table class="out">';
				echo '<tr>';
				for ($c=0; $c<$numColumn; ++$c) { 
					echo '<th>' . $headings[$c] . '</th>';
				}
				echo '</tr>';
				


				for ($c=0; $c<sizeof($url); ++$c ) {


					$handle = curl_init();
					curl_setopt($handle, CURLOPT_URL, $url[$c]);
					curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
					
					$target = curl_exec($handle);

					curl_close($handle);



					$dom = new DOMdocument();
					$dom->loadHTML($target);
					$dxp = new DomXPath($dom);



					// Extract all textual contents to a single string, and
					// then trim spaces and newlines from it.
					$contentArea = $dxp->query('//div[@id="loading-area"]');
					$content     = trim( $contentArea->item(0)->textContent );
					$content     = trimNewLines($content);
					$content     = trimSpaces($content);



					// Split the total content again and again to get different
					// cell values - split using the words that appear just
					// before and after the target values.
					// This splitting method may no longer work if the target
					// site structure is changed or different people's profiles
					// turn out to have different structures.

					$temp      = $dom->getElementsByTagName('h1')->item(0);
					$username  = trim($temp->textContent);

					// In case a profile does not have a timezone, split it
					// using "Languages" again.
					$temp      = explode( 'info',        $content )[1];
					$temp      = explode( 'Timezone',    $temp    )[0];
					$temp      = explode( 'Languages',   $temp    )[0];
					$area      = trim($temp);

					$temp      = explode( 'areas',       $content )[1];
					$temp      = explode( 'Experience',  $temp    )[0];
					$languages = trim($temp);

					$temp      = explode( 'completed:',  $content )[1];
					$temp      = explode( 'Words',       $temp    )[0];
					$tasks     = trim($temp);

					// In case there is no more relevant content in the HTML
					// page, split using the jQuery symbol "$", since that
					// usually seems to show up after the 'words translated'
					// part.
					$temp      = explode( 'translated:', $content )[1];
					$temp      = explode( 'Badges',      $temp    )[0];
					$temp      = explode( 'More',        $temp    )[0];
					$temp      = explode( '$',           $temp    )[0];
					$words     = trim($temp);

					$cellValues = array( 
						$username, $area, $languages, $tasks, $words
					);



					// Check for invalid values (for all but the usernames) by
					// setting limits on the lengths of the strings.
					// The limit for languages might truncate valid values,
					// since some profiles have multiple languages with
					// interconnections among them.
					$cellValues[1] = 
						( strlen($cellValues[1])>100 ) ? '' : $cellValues[1];
					$cellValues[2] = 
						( strlen($cellValues[2])>200 ) ? '' : $cellValues[2];
					$cellValues[3] = 
						( strlen($cellValues[3])>4   ) ? '' : $cellValues[3];
					$cellValues[4] = 
						( strlen($cellValues[4])>20  ) ? '' : $cellValues[4];


					
					// Insert a line break after each language pair (and
					// also each set of specializations).
					$cellValues[2] = separateLanguagePairs($cellValues[2]);



					// Shorten error message (some profile may not be publicly
					// accessible).
					$errMsg = 'The page you requested could not be found.';
					$cellValues[0] = ( $cellValues[0]==$errMsg ) ? 
						'[Failed to fetch]' : $cellValues[0];

					$cellValues[0] = '<a href="' . $url[$c] . '" ' 
						. 'target="_blank">' . $cellValues[0] . '</a>';



					// Create table row and put values in cells.
					echo '<tr>';
					for ($c2=0; $c2<$numColumn; ++$c2) {
						echo '<td>' . $cellValues[$c2] . '</td>';
					}
					echo '</tr>';

					time_nanosleep(0, rand(10,90)*10000000);


				} // Loop ends: data from one url


				echo '</table>';


			}

		?>

	</div>




</body>








</html>
