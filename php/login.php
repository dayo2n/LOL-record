<?php

session_start(); // 세션 시작 

$flag = false;
$_SESSION['id'] = $_POST["memberID"];
$_SESSION['pw'] = $_POST["memberPW"]; // 현재 로그인된 정보를 세션에 담음 

$filechk = file_exists("../data/member.json"); //회원정보가 담긴 파일이 있는지 체크 

$_SESSION['name'] = "";
if($filechk){// 파일있으면 읽기전용으로 열어놓고
  $fp = fopen("../data/member.json", "r");

  $userid = $_POST["memberID"];
  $userpw = $_POST["memberPW"];

  while(!feof($fp)){
    $info = fgets($fp);
    $data = json_decode($info, true);

    $existid = false; //존재하는 아이디를 발견했는지 표시하기 위한 변수
    foreach ($data as $key => $value) { // 아이디 존재 여부부터 확인

      if($flag){
        $_SESSION["name"] = $value;
      }
    if($existid){ //아이디가 존재하고
      if($key ==="pw" && $value === $userpw){ //비밀번호도 동일하면
        $flag = true;
      }
    }
    if(trim($key) === "id"){
      if(trim($value) === $userid){
         $existid = true;
        }
      }
    }
    if($existid) break;
  }
    if(!$existid){ //아이디가 존재하지 않는 경우 
      echo '<script>alert("입력하신 id가 존재하지 않거나 패스워드가 틀립니다.")</script>';
      echo '<script type="text/javascript">location.href = "../main.html";</script> '; 
        exit;
    }if(!$flag){ //비밀번호가 틀린 경우
      echo '<script>alert("입력하신 id가 존재하지 않거나 패스워드가 틀립니다.")</script>';
      echo '<script type="text/javascript">location.href = "../main.html";</script> '; 
        exit;
    }
  }else{ // 파일이 없는 경우 
    echo '<script>alert("입력하신 id가 존재하지 않거나 패스워드가 틀립니다.")</script>';
    echo '<script type="text/javascript">location.href = "../main.html";</script> '; 
      exit;
}

    //소환사 기본정보 
    $text = $_SESSION["name"];
    $summoner_name = urlencode($text);
    $api_key = "RGAPI-95ff83dc-f506-4fab-95c8-1d3a4396ce59"; //유출금지 :: personal api key
    $url = "https://kr.api.riotgames.com/lol/summoner/v4/summoners/by-name/".$summoner_name."?api_key=".$api_key; // 읽어온 값 json

    $is_post = false;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, $is_post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $response = curl_exec ($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // if($status_code == 200){
    //   var_dump($response);
    // }else{
    //   echo 'failed';
    // }
    $result = json_decode($response, true);
    // var_dump($result);


    $url = "https://kr.api.riotgames.com/lol/league/v4/entries/by-summoner/".$result['id']."?api_key=".$api_key;
 
    $ch = curl_init();
    $is_post = false;

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, $is_post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $response = curl_exec($ch);

    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    $league = json_decode($response, true);

    $unranked = array("tier" => "UNRANKED","rank"=>0, "leaguePoints" => 0, "wins" =>0, "losses"=>0);

    if($league[0]['queueType'] == "RANKED_SOLO_5x5" && $league[1]['queueType'] == "RANKED_FLEX_SR"){
      $soloRank = $league[0];
      $flexRank = $league[1];
    }else if($league[0]['queueType'] == "RANKED_FLEX_SR" && $league[1]['queueType'] == "RANKED_SOLO_5x5"){
      $flexRank = $league[0];
      $soloRank = $league[1];
    }else if($league[0]['queueType'] == "RANKED_FLEX_SR" && $league[1]==null){
      $flexRank = $league[0];
      $soloRank = $unranked;
    }else if($league[0]['queueType'] == "RANKED_SOLO_5x5" && $league[1]==null){
      $soloRank = $league[0];
      $flexRank = $unranked;
    }else{
      $soloRank = $unranked;
      $flexRank = $unranked;
    }

    $url = "https://kr.api.riotgames.com/lol/match/v4/matchlists/by-account/".$result['accountId']."?api_key=".$api_key;

    $ch = curl_init();
    $is_post = false;

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, $is_post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $response = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    $historyMatch = json_decode($response, true); // 전적에 대한 정보가 [] 리스트 형태로 :: matchlist

    $url = "https://kr.api.riotgames.com/lol/match/v4/matches/".$historyMatch['matches'][0]['gameId']."?api_key=".$api_key;
    $ch = curl_init();
    $is_post = false;

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, $is_post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $response = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    $tmpMatchInfo = json_decode($response, true); // 바로 직전 전적에 대한 소환사 10명의 정보

    $tmpParticipantName = $tmpMatchInfo['participantIdentities'];

    //전전판 전적
    $url = "https://kr.api.riotgames.com/lol/match/v4/matches/".$historyMatch['matches'][1]['gameId']."?api_key=".$api_key;
    $ch = curl_init();
    $is_post = false;

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, $is_post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $response = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    $tmpMatchInfo1 = json_decode($response, true); 

    $tmpParticipantName1 = $tmpMatchInfo1['participantIdentities'];


    //전전전판 
    $url = "https://kr.api.riotgames.com/lol/match/v4/matches/".$historyMatch['matches'][2]['gameId']."?api_key=".$api_key;
    $ch = curl_init();
    $is_post = false;

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, $is_post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $response = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    $tmpMatchInfo2 = json_decode($response, true); 

    $tmpParticipantName2 = $tmpMatchInfo2['participantIdentities'];

?>

<!-- 로그인하면 보여주는 내 정보 페이지  -->
<html>
  <head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="../css/style.css" />
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.12.0/css/all.css"
      integrity="sha384-REHJTs1r2ErKBuJB0fCK99gCYsVjwxHrSU0N7I1zl9vZbggVJXRMsv/sLlOAGb4M"
      crossorigin="anonymous"
    />
    <title>League of Legends | @dayo2n</title>
  </head>
  <body>
    <span class="header">
      <span class="left">
        <img
          src="../image/LogoLOL.png"
          alt=""
          id="imgLOLlogo"
          onclick='location.href="../main.html"'
        />
      </span>
      <span class="right">
        <form action="./search.php" method="GET">
          <input type="text" placeholder="소환사명 ..." class="input-txt" name="summoner_name" />
          <button type="submit" class="searchIcon" id="summonerSearchBtn">
            <i class="fas fa-search fa-lg"></i>
          </button>
        </form>
      </span>
    </span>
    <span class="main_search">
      <fieldset class="fieldset_summoner">
        <span><?php echo $_SESSION['id'] ?> 로그인 성공</span>
        <br />
        <img
          src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $result['profileIconId']; ?>.png"
          alt=""
          id="summoner_icon"
        />
        <br />
        <br />
        <span
          >내 소환사 이름:
          <?php echo $text;?></span
        >
        <br />
        <span
          >내 소환사 레벨:
          <?php echo $result['summonerLevel'];?></span
        >
        <div class="tier_solo">
          <fieldset>
            <legend><h3>솔로랭크</h3></legend>
            <img
              src="../image/rankEmblem/Emblem_<?php echo $soloRank['tier'];?>.jpg"
              alt="no image"
              id="summoner_tier_img"
            />
            <div class="summoner_tier_text">
              <span id="summoner_tier_is"
                >티어:
                <?php echo $soloRank['tier']; echo " ".$league[0]['rank'];?>
              </span>
              <br />
              <span id="summoner_lp_is"
                >LP:
                <?php echo $soloRank['leaguePoints'];?>
              </span>
              <br />
              <span
                >승:
                <?php echo $soloRank['wins'];?>
                패:
                <?php echo $soloRank['losses'];?>
              </span>
              <br />
              <span>
                총
                <?php echo $soloRank['wins'] + $soloRank['losses'];?>
                판 플레이
              </span>
            </div>
          </fieldset>
        </div>
        <div class="tier_flex">
          <fieldset>
            <legend><h3>자유랭크</h3></legend>
            <img
              src="../image/rankEmblem/Emblem_<?php echo $flexRank['tier'];?>.jpg"
              alt="no image"
              id="summoner_tier_img"
            />
            <div class="summoner_tier_text">
              <span id="summoner_tier_is"
                >티어:
                <?php echo $flexRank['tier']; echo " ".$league[0]['rank'];?></span
              >
              <br />
              <span id="summoner_lp_is"
                >LP:
                <?php echo $flexRank['leaguePoints'];?></span
              >
              <br />
              <span
                >승:
                <?php echo $flexRank['wins'];?>
                패:
                <?php echo $flexRank['losses'];?></span
              >
              <br />
              <span>
                총
                <?php echo $flexRank['wins'] + $flexRank['losses'];?>
                판 플레이
              </span>
            </div>
          </fieldset>
        </div>
      </fieldset>
      <fieldset class="fieldset_match">
        <div class="playHistory">
          <p>PLAY HISTORY
          </p>
          <fieldset class="fieldset_history"> 
            <!-- 전 판 -->
            <div class="blueTeam">
              <p style="text-shadow: 2px 3px blue">blue team</p>                  
              <span>
                <?php echo $tmpParticipantName[0]['player']['summonerName'];?></span
              >
              <br />

              <span>
              <?php echo $tmpParticipantName[1]['player']['summonerName'];?></span
              >
              <br />
              <span>
              <?php echo $tmpParticipantName[2]['player']['summonerName'];?></span
              >
              <br />
              <span>
              <?php echo $tmpParticipantName[3]['player']['summonerName'];?></span
              >
              <br />
              <span>
                <?php echo $tmpParticipantName[4]['player']['summonerName'];?></span
              >
            </div>
            <div class="redTeam">
              <p style="text-shadow: 2px 3px red">red team</p>
              <span>
                <?php echo $tmpParticipantName[5]['player']['summonerName'];?></span
              >
              <br />
              <span>
                <?php echo $tmpParticipantName[6]['player']['summonerName'];?></span
              >
              <br />
              <span>
                <?php echo $tmpParticipantName[7]['player']['summonerName'];?></span
              >
              <br />
              <span>
                <?php echo $tmpParticipantName[8]['player']['summonerName'];?></span
              >
              <br />
              <span>
                <?php echo $tmpParticipantName[9]['player']['summonerName'];?></span
              >
            </div>
          </fieldset>
          <!-- 전전판 -->
          <fieldset class="fieldset_history">
            <div class="blueTeam">
              <p style="text-shadow: 2px 3px blue">blue team</p>                  
              <span>
                <?php echo $tmpParticipantName1[0]['player']['summonerName'];?></span
              >
              <br />

              <span>
              <?php echo $tmpParticipantName1[1]['player']['summonerName'];?></span
              >
              <br />
              <span>
              <?php echo $tmpParticipantName1[2]['player']['summonerName'];?></span
              >
              <br />
              <span>
              <?php echo $tmpParticipantName1[3]['player']['summonerName'];?></span
              >
              <br />
              <span>
                <?php echo $tmpParticipantName1[4]['player']['summonerName'];?></span
              >
            </div>
            <div class="redTeam">
              <p style="text-shadow: 2px 3px red">red team</p>
              <span>
                <?php echo $tmpParticipantName1[5]['player']['summonerName'];?></span
              >
              <br />
              <span>
                <?php echo $tmpParticipantName1[6]['player']['summonerName'];?></span
              >
              <br />
              <span>
                <?php echo $tmpParticipantName1[7]['player']['summonerName'];?></span
              >
              <br />
              <span>
                <?php echo $tmpParticipantName1[8]['player']['summonerName'];?></span
              >
              <br />
              <span>
                <?php echo $tmpParticipantName1[9]['player']['summonerName'];?></span
              >
            </div>
          </fieldset>
          <fieldset class="fieldset_history">
            <div class="blueTeam">
              <p style="text-shadow: 2px 3px blue">blue team</p>                  
              <span>
                <?php echo $tmpParticipantName2[0]['player']['summonerName'];?></span
              >
              <br />

              <span>
              <?php echo $tmpParticipantName2[1]['player']['summonerName'];?></span
              >
              <br />
              <span>
              <?php echo $tmpParticipantName2[2]['player']['summonerName'];?></span
              >
              <br />
              <span>
              <?php echo $tmpParticipantName2[3]['player']['summonerName'];?></span
              >
              <br />
              <span>
                <?php echo $tmpParticipantName2[4]['player']['summonerName'];?></span
              >
            </div>
            <div class="redTeam">
              <p style="text-shadow: 2px 3px red">red team</p>
              <span>
                <?php echo $tmpParticipantName2[5]['player']['summonerName'];?></span
              >
              <br />
              <span>
                <?php echo $tmpParticipantName2[6]['player']['summonerName'];?></span
              >
              <br />
              <span>
                <?php echo $tmpParticipantName2[7]['player']['summonerName'];?></span
              >
              <br />
              <span>
                <?php echo $tmpParticipantName2[8]['player']['summonerName'];?></span
              >
              <br />
              <span>
                <?php echo $tmpParticipantName2[9]['player']['summonerName'];?></span
              >
            </div>
          </fieldset>
        </div>
      </fieldset>
  </body>
</html>

