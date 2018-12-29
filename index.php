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




	<!-- <noscript></noscript> -->


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



			function addNewlineEveryThreeWords($string) {

				$array = explode(' ', $string);

				for ($c=0; $c<sizeof($array); ++$c) {
					if ( ($c+1)%3==0 ) {
						$array[$c] .= '<br>';
					}
				}

				return implode(' ', $array);


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



					$cellValues[2] = addNewlineEveryThreeWords($cellValues[2]);



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