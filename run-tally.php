<?php
//Read the text files and find the anomolies
$files = array(
  'ArizonaEdisonData.txt',
  'GeorgiaEdisonData.txt',
  'MichiganEdisonData.txt',
  'NevadaEdisonData.txt',
  'PennsylvaniaEdisonData.txt',
  'WisconsinEdisonData.txt'
);

$chars = array('T','Z');

foreach($files as $f){

  $file = fopen("votes/$f", "r");
  $votes = array();

  while (!feof($file)) {


    $line = fgets($file);
    //Explode into Timestamp 0, TotalVotes 1, BidenVoterShare 2, BidenVotes 3, TrumpVoterShare 4, TrumpVoterShare 5
    //
    $line_arr = explode(",", $line);
    if ($line_arr[0] != 'Timestamp'){
      $t = array(
        "TimeStamp"=>$line_arr[0],
        "TotalVotes"=>$line_arr[1],
        "BidenVoterShare"=>$line_arr[2],
        "BidenVotes"=>$line_arr[3],
        "TrumpVoterShare"=>$line_arr[4],
        "TrumpVotes"=>$line_arr[5]
      );

      array_push($votes,$t);
    }

  }

  fclose($file);

  //var_dump($votes[0]);
  //print '<br /><br />';
  ?>
  <h1><?= $f ?></h1>
  <table width="80%">
    <thead>
      <th>Time</th>
      <th>Trump Current</th>
      <th>Trump Next</th>
      <th>Trump Diff</th>
      <th>Biden Current</th>
      <th>Biden Next</th>
      <th>Diff</th>
    </thead>
    <tbody>
  <?php
  /*
  var_dump($votes[0]);
  print '<br /><br />';
  print sizeof($votes).' lines in file.';
  */
  $instances = 0;
  $curr_total = $next_total = $trump_difference = $trump_total_difference = 0;
  $biden_curr = $biden_next = $biden_difference = $biden_total_difference = 0;
  // Now, go through votes array and find lines where trump tally decreases from previous line
  for ($i=0; $i<sizeof($votes); $i++){
    //Get trump counts for current and next line in arr
    $curr_total = (int) $votes[$i]['TrumpVotes'];
    $next_total = (int) $votes[$i+1]['TrumpVotes'];

    //If there is a NEGATIVE tally in sequence...
    if ($curr_total > $next_total){
      if ($next_total > 0){
        //Get the Biden counts for comparison
        $biden_curr = (int) $votes[$i]['BidenVotes'];
        $biden_next = (int) $votes[$i+1]['BidenVotes'];
        $biden_difference = $biden_next - $biden_curr;
        $biden_total_difference += $biden_difference;

        $time = str_replace($chars,' ',$votes[$i+1]['TimeStamp']);

        $instances++;
        $trump_difference = $next_total - $curr_total;
        $trump_total_difference += $trump_difference;

        //print $votes[$i+1]['Timestamp'].'<br />';
        print '<tr><td>'.$time.
              '</td><td>'.$curr_total.
              '</td><td>'.$next_total.
              '</td><td>'.number_format($trump_difference).
              ' votes.</td>'.
              '</td><td>'.$biden_curr.
              '</td><td>'.$biden_next.
              '</td><td>'.number_format($biden_difference).
              ' votes.</td></tr>';
      }
    }
  }
  ?>
  </tbody>
  </table>
  <?php
  print '<p>'.$instances." times that this happens the $f file.</p>";
  print '<p>Trump Vote Loss/Gain:  '.$trump_total_difference.'</p>';
  print '<p>Biden Vote Loss/Gain:  '.$biden_total_difference.'</p>';

}
