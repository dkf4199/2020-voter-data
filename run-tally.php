<?php
//Read the text file and find the anomoly

$files = array(
  'ArizonaEdisonData.txt',
  'GeorgiaEdisonData.txt',
  'MichiganEdisonData.txt',
  'NevadaEdisonData.txt',
  'PennsylvaniaEdisonData.txt',
  'WisconsinEdisonData.txt'
);

$chars = array('T','Z');
/*
foreach($files as $f){

  $count = 0;
  $myfile = fopen("./votes/$f", "r") or die("Unable to open $f");
  // Output one line until end-of-file
  while(!feof($myfile)) {
    $count++;
  }

  print $f.' : '.$count.' lines.<br />';
}
*/
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
  <table width="60%">
    <thead>
      <th>Time</th>
      <th>Current Total</th>
      <th>Next Total</th>
      <th>Difference</th>
    </thead>
    <tbody>
  <?php
  /*
  var_dump($votes[0]);
  print '<br /><br />';
  print sizeof($votes).' lines in file.';
  */
  $instances = 0;
  $curr_total = $next_total = $total_difference = 0;
  // Now, go through votes array and find lines where trump tally decreases from previous line
  for ($i=0; $i<sizeof($votes); $i++){
    $curr_total = (int) $votes[$i]['TrumpVotes'];
    $next_total = (int) $votes[$i+1]['TrumpVotes'];


    if ($curr_total > $next_total){
      if ($next_total > 0){
        $time = str_replace($chars,' ',$votes[$i]['TimeStamp']);

        $instances++;
        $difference = $curr_total - $next_total;
        $total_difference += $difference;

        //print $votes[$i+1]['Timestamp'].'<br />';
        print '<tr><td>'.$time.'</td><td>'.$curr_total.'</td><td>'.$next_total.'</td><td>-'.number_format($difference).' votes.</td></tr>';
      }
    }
  }
  ?>
  </tbody>
  </table>
  <?php
  print '<p>'.$instances." times that this happens the $f file.</p>";
  print '<p>Total Vote Loss:  -'.$total_difference.'</p>';

}
