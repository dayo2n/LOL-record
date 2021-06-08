<!-- 소환사 전적검색 페이지 -->
<?php

    //소환사 기본정보 
    $api_key = "RGAPI-95ff83dc-f506-4fab-95c8-1d3a4396ce59"; //유출금지 :: personal api key
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

    $gameList = json_decode($response, true);

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
          src="../image/LogoLOL"
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
    <span class="main">
      <fieldset class="fieldset_summoner_blue">
        <img
          src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $gameList[gameList][0][participants][0][profileIconId]; ?>.png"
          alt="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/4561.png"
          id="spec_summoner_icon"
        />
        <span
          >
          <?php echo $gameList[gameList][0][participants][0][summonerName];?></span
        >
        <br />
        <img
          src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $gameList[gameList][0][participants][1][profileIconId]; ?>.png"
          alt="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/4561.png"
          id="spec_summoner_icon"
        />
        <span
          >
          <?php echo $gameList[gameList][0][participants][1][summonerName];?></span
        >
        <br />
        <img
          src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $gameList[gameList][0][participants][2][profileIconId]; ?>.png"
          alt="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/4561.png"
          id="spec_summoner_icon"
        />
        <span
          >
          <?php echo $gameList[gameList][0][participants][2][summonerName];?></span
        >
        <br />
        <img
          src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $gameList[gameList][0][participants][3][profileIconId]; ?>.png"
          alt="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/4561.png"
          id="spec_summoner_icon"
        />
        <span
          >
          <?php echo $gameList[gameList][0][participants][3][summonerName];?></span
        >
        <br />
        <img
          src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $gameList[gameList][0][participants][4][profileIconId]; ?>.png"
          alt="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/4561.png"
          id="spec_summoner_icon"
        />
        <span
          >
          <?php echo $gameList[gameList][0][participants][4][summonerName];?></span
        >
        <br />
        </fieldset>
        <fieldset class="fieldset_summoner_red">
        <img
          src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $gameList[gameList][0][participants][5][profileIconId]; ?>.png"
          alt="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/4561.png"
          id="spec_summoner_icon"
        />
        <span
          >
          <?php echo $gameList[gameList][0][participants][5][summonerName];?></span
        >
        <br />
        <img
          src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $gameList[gameList][0][participants][6][profileIconId]; ?>.png"
          alt="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/4561.png"
          id="spec_summoner_icon"
        />
        <span
          >
          <?php echo $gameList[gameList][0][participants][6][summonerName];?></span
        >
        <br />
        <img
          src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $gameList[gameList][0][participants][7][profileIconId]; ?>.png"
          alt="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/4561.png"
          id="spec_summoner_icon"
        />
        <span
          >
          <?php echo $gameList[gameList][0][participants][7][summonerName];?></span
        >
        <br />
        <img
          src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $gameList[gameList][0][participants][8][profileIconId]; ?>.png"
          alt="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/4561.png"
          id="spec_summoner_icon"
        />
        <span
          >
          <?php echo $gameList[gameList][0][participants][8][summonerName];?></span
        >
        <br />
        <img
          src="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/<?php echo $gameList[gameList][0][participants][9][profileIconId]; ?>.png"
          alt="http://ddragon.leagueoflegends.com/cdn/10.11.1/img/profileicon/4561.png"
          id="spec_summoner_icon"
        />
        <span
          >
          <?php echo $gameList[gameList][0][participants][9][summonerName];?></span
        >
        <br />
        </fieldset>
    </span>
    <!-- <span id="tail"> </span> --> 
    <!-- 지난 전적 담기 -->
  </body>
</html>
