<!-- 소환사 전적검색 페이지 -->
<?php

    //챌린저 리그 정보
    $api_key = '{api-key}'; //유출금지 :: personal api key
    $url = "https://kr.api.riotgames.com/lol/league/v4/challengerleagues/by-queue/RANKED_SOLO_5x5?api_key=".$api_key;

    $is_post = false;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, $is_post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $response = curl_exec ($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $challenger = json_decode($response, true);
    $challenger = $challenger[entries];
    

    //소환사 기본정보
    $summoner = array();
    for($i=0; $i<10; $i++){
        $summoner_name = $challenger[$i][summonerName];
        $url = "https://kr.api.riotgames.com/lol/summoner/v4/summoners/by-name/".$summoner_name."?api_key=".$api_key;

        $is_post = false;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, $is_post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec ($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);
        array_push($summoner, $result);
    }

    $summoner_league = array();
    for($i=0; $i<10; $i++){
    //리그포인트 정보 가져오기
    $url = "https://kr.api.riotgames.com/lol/league/v4/entries/by-summoner/".$summoner[$i][id]."?api_key=".$api_key;
 
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

    if($league[0][queueType] == "RANKED_SOLO_5x5" && $league[1][queueType] == "RANKED_FLEX_SR"){
      $soloRank = $league[0];
    }else if($league[0][queueType] == "RANKED_FLEX_SR" && $league[1][queueType] == "RANKED_SOLO_5x5"){
      $soloRank = $league[1];
    }else if($league[0][queueType] == "RANKED_FLEX_SR" && $league[1]==null){
      $soloRank = $unranked;
    }else if($league[0][queueType] == "RANKED_SOLO_5x5" && $league[1]==null){
      $soloRank = $league[0];
    }else{
      $soloRank = $unranked;
    }
    array_push($summoner_league, $soloRank);
}

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
    <span class="main_challenger">
        <legend>League of CHALLENGER</legend>
        <br>
      <span class="double_column">
        <fieldset class="fieldset_challenger_summoner">
          <img
            src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $summoner[0][profileIconId]; ?>.png"
            alt="profile icon"
            id="summoner_icon"
          />
          <br />
          <br />
          <span
            >소환사 이름:
            <?php echo $challenger[0][summonerName];?></span
          >
          <br />
          <span
            >소환사 레벨:
            <?php echo $challenger[0][summonerLevel];?></span
          >
          <div class="tier_solo">
            <fieldset>
              <img
                src="../image/rankEmblem/Emblem_CHALLENGER.jpg"
                alt="no image"
                id="summoner_tier_img"
              />
              <div class="summoner_tier_text">
                <span id="summoner_tier_is"
                  >티어:
                  <?php echo $summoner_league[0][tier]; echo " ".$summoner_league[0][rank];?>
                </span>
                <br />
                <span id="summoner_lp_is"
                  >LP:
                  <?php echo $summoner_league[0][leaguePoints];?>
                </span>
                <br />
                <span
                  >승:
                  <?php echo $summoner_league[0][wins];?>
                  패:
                  <?php echo $summoner_league[0][losses];?>
                </span>
                <br />
                <span>
                  총
                  <?php echo $summoner_league[0][wins] + $summoner_league[0][losses];?>
                  판 플레이
                </span>
              </div>
            </fieldset>
          </div>
        </fieldset>
        <fieldset class="fieldset_challenger_summoner">
          <img
            src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $summoner[1][profileIconId]; ?>.png"
            alt="profile icon"
            id="summoner_icon"
          />
          <br />
          <br />
          <span
            >소환사 이름:
            <?php echo $challenger[1][summonerName];?></span
          >
          <br />
          <span
            >소환사 레벨:
            <?php echo $challenger[1][summonerLevel];?></span
          >
          <div class="tier_solo">
            <fieldset>
              <img
                src="../image/rankEmblem/Emblem_CHALLENGER.jpg"
                alt="no image"
                id="summoner_tier_img"
              />
              <div class="summoner_tier_text">
                <span id="summoner_tier_is"
                  >티어:
                  <?php echo $summoner_league[1][tier]; echo " ".$summoner_league[1][rank];?>
                </span>
                <br />
                <span id="summoner_lp_is"
                  >LP:
                  <?php echo $summoner_league[1][leaguePoints];?>
                </span>
                <br />
                <span
                  >승:
                  <?php echo $summoner_league[1][wins];?>
                  패:
                  <?php echo $summoner_league[1][losses];?>
                </span>
                <br />
                <span>
                  총
                  <?php echo $summoner_league[1][wins] + $summoner_league[1][losses];?>
                  판 플레이
                </span>
              </div>
            </fieldset>
          </div>
        </fieldset>
      </span>
      <span class="double_column">
        <fieldset class="fieldset_challenger_summoner">
          <img
            src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $summoner[2][profileIconId]; ?>.png"
            alt="profile icon"
            id="summoner_icon"
          />
          <br />
          <br />
          <span
            >소환사 이름:
            <?php echo $challenger[2][summonerName];?></span
          >
          <br />
          <span
            >소환사 레벨:
            <?php echo $challenger[2][summonerLevel];?></span
          >
          <div class="tier_solo">
            <fieldset>
              <img
                src="../image/rankEmblem/Emblem_CHALLENGER.jpg"
                alt="no image"
                id="summoner_tier_img"
              />
              <div class="summoner_tier_text">
                <span id="summoner_tier_is"
                  >티어:
                  <?php echo $summoner_league[2][tier]; echo " ".$summoner_league[2][rank];?>
                </span>
                <br />
                <span id="summoner_lp_is"
                  >LP:
                  <?php echo $summoner_league[2][leaguePoints];?>
                </span>
                <br />
                <span
                  >승:
                  <?php echo $summoner_league[2][wins];?>
                  패:
                  <?php echo $summoner_league[2][losses];?>
                </span>
                <br />
                <span>
                  총
                  <?php echo $summoner_league[2][wins] + $summoner_league[2][losses];?>
                  판 플레이
                </span>
              </div>
            </fieldset>
          </div>
        </fieldset>
        <fieldset class="fieldset_challenger_summoner">
          <img
            src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $summoner[3][profileIconId]; ?>.png"
            alt="profile icon"
            id="summoner_icon"
          />
          <br />
          <br />
          <span
            >소환사 이름:
            <?php echo $challenger[3][summonerName];?></span
          >
          <br />
          <span
            >소환사 레벨:
            <?php echo $challenger[3][summonerLevel];?></span
          >
          <div class="tier_solo">
            <fieldset>
              <img
                src="../image/rankEmblem/Emblem_CHALLENGER.jpg"
                alt="no image"
                id="summoner_tier_img"
              />
              <div class="summoner_tier_text">
                <span id="summoner_tier_is"
                  >티어:
                  <?php echo $summoner_league[3][tier]; echo " ".$summoner_league[3][rank];?>
                </span>
                <br />
                <span id="summoner_lp_is"
                  >LP:
                  <?php echo $summoner_league[3][leaguePoints];?>
                </span>
                <br />
                <span
                  >승:
                  <?php echo $summoner_league[3][wins];?>
                  패:
                  <?php echo $summoner_league[3][losses];?>
                </span>
                <br />
                <span>
                  총
                  <?php echo $summoner_league[3][wins] + $summoner_league[3][losses];?>
                  판 플레이
                </span>
              </div>
            </fieldset>
          </div>
        </fieldset>
      </span>
      <span class="double_column">
        <fieldset class="fieldset_challenger_summoner">
          <img
            src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $summoner[4][profileIconId]; ?>.png"
            alt="profile icon"
            id="summoner_icon"
          />
          <br />
          <br />
          <span
            >소환사 이름:
            <?php echo $challenger[4][summonerName];?></span
          >
          <br />
          <span
            >소환사 레벨:
            <?php echo $challenger[4][summonerLevel];?></span
          >
          <div class="tier_solo">
            <fieldset>
              <img
                src="../image/rankEmblem/Emblem_CHALLENGER.jpg"
                alt="no image"
                id="summoner_tier_img"
              />
              <div class="summoner_tier_text">
                <span id="summoner_tier_is"
                  >티어:
                  <?php echo $summoner_league[4][tier]; echo " ".$summoner_league[4][rank];?>
                </span>
                <br />
                <span id="summoner_lp_is"
                  >LP:
                  <?php echo $summoner_league[4][leaguePoints];?>
                </span>
                <br />
                <span
                  >승:
                  <?php echo $summoner_league[4][wins];?>
                  패:
                  <?php echo $summoner_league[4][losses];?>
                </span>
                <br />
                <span>
                  총
                  <?php echo $summoner_league[4][wins] + $summoner_league[4][losses];?>
                  판 플레이
                </span>
              </div>
            </fieldset>
          </div>
        </fieldset>
        <fieldset class="fieldset_challenger_summoner">
          <img
            src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $summoner[5][profileIconId]; ?>.png"
            alt="profile icon"
            id="summoner_icon"
          />
          <br />
          <br />
          <span
            >소환사 이름:
            <?php echo $challenger[5][summonerName];?></span
          >
          <br />
          <span
            >소환사 레벨:
            <?php echo $challenger[5][summonerLevel];?></span
          >
          <div class="tier_solo">
            <fieldset>
              <img
                src="../image/rankEmblem/Emblem_CHALLENGER.jpg"
                alt="no image"
                id="summoner_tier_img"
              />
              <div class="summoner_tier_text">
                <span id="summoner_tier_is"
                  >티어:
                  <?php echo $summoner_league[5][tier]; echo " ".$summoner_league[5][rank];?>
                </span>
                <br />
                <span id="summoner_lp_is"
                  >LP:
                  <?php echo $summoner_league[5][leaguePoints];?>
                </span>
                <br />
                <span
                  >승:
                  <?php echo $summoner_league[5][wins];?>
                  패:
                  <?php echo $summoner_league[5][losses];?>
                </span>
                <br />
                <span>
                  총
                  <?php echo $summoner_league[5][wins] + $summoner_league[5][losses];?>
                  판 플레이
                </span>
              </div>
            </fieldset>
          </div>
        </fieldset>
      </span>
      <span class="double_column">
        <fieldset class="fieldset_challenger_summoner">
          <img
            src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $summoner[6][profileIconId]; ?>.png"
            alt="profile icon"
            id="summoner_icon"
          />
          <br />
          <br />
          <span
            >소환사 이름:
            <?php echo $challenger[6][summonerName];?></span
          >
          <br />
          <span
            >소환사 레벨:
            <?php echo $challenger[6][summonerLevel];?></span
          >
          <div class="tier_solo">
            <fieldset>
              <img
                src="../image/rankEmblem/Emblem_CHALLENGER.jpg"
                alt="no image"
                id="summoner_tier_img"
              />
              <div class="summoner_tier_text">
                <span id="summoner_tier_is"
                  >티어:
                  <?php echo $summoner_league[6][tier]; echo " ".$summoner_league[6][rank];?>
                </span>
                <br />
                <span id="summoner_lp_is"
                  >LP:
                  <?php echo $summoner_league[6][leaguePoints];?>
                </span>
                <br />
                <span
                  >승:
                  <?php echo $summoner_league[6][wins];?>
                  패:
                  <?php echo $summoner_league[6][losses];?>
                </span>
                <br />
                <span>
                  총
                  <?php echo $summoner_league[6][wins] + $summoner_league[6][losses];?>
                  판 플레이
                </span>
              </div>
            </fieldset>
          </div>
        </fieldset>
        <fieldset class="fieldset_challenger_summoner">
          <img
            src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $summoner[7][profileIconId]; ?>.png"
            alt="profile icon"
            id="summoner_icon"
          />
          <br />
          <br />
          <span
            >소환사 이름:
            <?php echo $challenger[7][summonerName];?></span
          >
          <br />
          <span
            >소환사 레벨:
            <?php echo $challenger[7][summonerLevel];?></span
          >
          <div class="tier_solo">
            <fieldset>
              <img
                src="../image/rankEmblem/Emblem_CHALLENGER.jpg"
                alt="no image"
                id="summoner_tier_img"
              />
              <div class="summoner_tier_text">
                <span id="summoner_tier_is"
                  >티어:
                  <?php echo $summoner_league[7][tier]; echo " ".$summoner_league[7][rank];?>
                </span>
                <br />
                <span id="summoner_lp_is"
                  >LP:
                  <?php echo $summoner_league[7][leaguePoints];?>
                </span>
                <br />
                <span
                  >승:
                  <?php echo $summoner_league[7][wins];?>
                  패:
                  <?php echo $summoner_league[7][losses];?>
                </span>
                <br />
                <span>
                  총
                  <?php echo $summoner_league[7][wins] + $summoner_league[7][losses];?>
                  판 플레이
                </span>
              </div>
            </fieldset>
          </div>
        </fieldset>
      </span>
      <span class="double_column">
        <fieldset class="fieldset_challenger_summoner">
          <img
            src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $summoner[8][profileIconId]; ?>.png"
            alt="profile icon"
            id="summoner_icon"
          />
          <br />
          <br />
          <span
            >소환사 이름:
            <?php echo $challenger[8][summonerName];?></span
          >
          <br />
          <span
            >소환사 레벨:
            <?php echo $challenger[8][summonerLevel];?></span
          >
          <div class="tier_solo">
            <fieldset>
              <img
                src="../image/rankEmblem/Emblem_CHALLENGER.jpg"
                alt="no image"
                id="summoner_tier_img"
              />
              <div class="summoner_tier_text">
                <span id="summoner_tier_is"
                  >티어:
                  <?php echo $summoner_league[8][tier]; echo " ".$summoner_league[8][rank];?>
                </span>
                <br />
                <span id="summoner_lp_is"
                  >LP:
                  <?php echo $summoner_league[8][leaguePoints];?>
                </span>
                <br />
                <span
                  >승:
                  <?php echo $summoner_league[8][wins];?>
                  패:
                  <?php echo $summoner_league[8][losses];?>
                </span>
                <br />
                <span>
                  총
                  <?php echo $summoner_league[8][wins] + $summoner_league[8][losses];?>
                  판 플레이
                </span>
              </div>
            </fieldset>
          </div>
        </fieldset>
        <fieldset class="fieldset_challenger_summoner">
          <img
            src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $summoner[9][profileIconId]; ?>.png"
            alt="profile icon"
            id="summoner_icon"
          />
          <br />
          <br />
          <span
            >소환사 이름:
            <?php echo $challenger[9][summonerName];?></span
          >
          <br />
          <span
            >소환사 레벨:
            <?php echo $challenger[9][summonerLevel];?></span
          >
          <div class="tier_solo">
            <fieldset>
              <img
                src="../image/rankEmblem/Emblem_CHALLENGER.jpg"
                alt="no image"
                id="summoner_tier_img"
              />
              <div class="summoner_tier_text">
                <span id="summoner_tier_is"
                  >티어:
                  <?php echo $summoner_league[9][tier]; echo " ".$summoner_league[9][rank];?>
                </span>
                <br />
                <span id="summoner_lp_is"
                  >LP:
                  <?php echo $summoner_league[9][leaguePoints];?>
                </span>
                <br />
                <span
                  >승:
                  <?php echo $summoner_league[9][wins];?>
                  패:
                  <?php echo $summoner_league[9][losses];?>
                </span>
                <br />
                <span>
                  총
                  <?php echo $summoner_league[9][wins] + $summoner_league[9][losses];?>
                  판 플레이
                </span>
              </div>
            </fieldset>
          </div>
        </fieldset>
      </span>
    </span>
  </body>
</html>
