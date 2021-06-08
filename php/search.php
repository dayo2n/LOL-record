<!-- 소환사 전적검색 페이지 -->
<?php

    //소환사 기본정보 
    $text = $_GET['summoner_name'];
    $summoner_name = urlencode($text);
    $api_key = '{api-key}'; //유출금지 :: personal api key
    $url = "https://kr.api.riotgames.com/lol/summoner/v4/summoners/by-name/".$summoner_name."?api_key=".$api_key;

    $ch = curl_init();
    $is_post = false;

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, $is_post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec ($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $result = json_decode($response, true);

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
    
    $url = "https://kr.api.riotgames.com/lol/spectator/v4/active-games/by-summoner/".$result['id']."?api_key=".$api_key;
 
    $ch = curl_init();
    $is_post = false;

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, $is_post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $response = curl_exec($ch);

    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    $presentMatch = json_decode($response, true);
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
    <!-- 소환사 정보 -->
    <span class="main_search">
      <fieldset class="fieldset_summoner">
        <img
          src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $result['profileIconId']; ?>.png"
          alt=""
          id="summoner_icon"
        />
        <br />
        <br />
        <span
          >소환사 이름:
          <?php echo $text;?></span
        >
        <br />
        <span
          >소환사 레벨:
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
        <div class="nowPlay">
<!-- 현재 플레이 중인 게임 정보 -->
        <p>NOW PLAY</p>
          <fieldset>
          <div class="blueTeam">
            <p style="text-shadow: 2px 3px blue">blue team</p>
            <img
              src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $presentMatch['participants'][0]['profileIconId']; ?>.png"
              alt="no img"
              id="spec_summoner_icon"
            />
            <span>
              <?php echo $presentMatch['participants'][0]['summonerName'];?></span
            >
            <br />
            <img
              src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $presentMatch['participants'][1]['profileIconId']; ?>.png"
              alt="no img"
              id="spec_summoner_icon"
            />
            <span>
              <?php echo $presentMatch['participants'][1]['summonerName'];?></span
            >
            <br />
            <img
              src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $presentMatch['participants'][2]['profileIconId']; ?>.png"
              alt="no img"
              id="spec_summoner_icon"
            />
            <span>
              <?php echo $presentMatch['participants'][2]['summonerName'];?></span
            >
            <br />
            <img
              src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $presentMatch['participants'][3]['profileIconId']; ?>.png"
              alt="no img"
              id="spec_summoner_icon"
            />
            <span>
              <?php echo $presentMatch['participants'][3]['summonerName'];?></span
            >
            <br />
            <img
              src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $presentMatch['participants'][4]['profileIconId']; ?>.png"
              alt="no img"
              id="spec_summoner_icon"
            />
            <span>
              <?php echo $presentMatch['participants'][4]['summonerName'];?></span
            >
          </div>
          <div class="redTeam">
            <p style="text-shadow: 2px 3px red">red team</p>
            <img
              src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $presentMatch['participants'][5]['profileIconId']; ?>.png"
              alt="no img"
              id="spec_summoner_icon"
            />
            <span>
              <?php echo $presentMatch['participants'][5]['summonerName'];?></span
            >
            <br />
            <img
              src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $presentMatch['participants'][6]['profileIconId']; ?>.png"
              alt="no img"
              id="spec_summoner_icon"
            />
            <span>
              <?php echo $presentMatch['participants'][6]['summonerName'];?></span
            >
            <br />
            <img
              src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $presentMatch['participants'][7]['profileIconId']; ?>.png"
              alt="no img"
              id="spec_summoner_icon"
            />
            <span>
              <?php echo $presentMatch['participants'][7]['summonerName'];?></span
            >
            <br />
            <img
              src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $presentMatch['participants'][8]['profileIconId']; ?>.png"
              alt="no img"
              id="spec_summoner_icon"
            />
            <span>
              <?php echo $presentMatch['participants'][8]['summonerName'];?></span
            >
            <br />
            <img
              src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $presentMatch['participants'][9]['profileIconId']; ?>.png"
              alt="no img"
              id="spec_summoner_icon"
            />
            <span>
              <?php echo $presentMatch['participants'][9]['summonerName'];?></span
            >
          </div>
          </fieldset>
        </div>

        <div class="playHistory">
          <p>PLAY HISTORY
          </p>
          <fieldset class="fieldset_history"> 
            <!-- 전 판 전적 -->
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
          <!-- 전전판 전적 -->
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
    <!-- 지난 전적 담기 -->
  </body>
</html>

