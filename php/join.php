
      <?php

        $userid = $_POST["memberID"];
        $userpw = $_POST["memberPW"];//입력된 아이디와 패스워드 
        $username = $_POST["memberName"];
  
        $member = array("id"=>$userid, "pw"=>$userpw, "name"=>$username); //배열로 생성 
        $joining = json_encode($member); // json객체로 encode

        $filechk = file_exists("../data/member.json"); //json 파일이 존재하는지 체크

        if($filechk){ // 파일있으면 읽기전용으로 열어놓고
          $fp = fopen("../data/member.json", "r");

          while(!feof($fp)){ //파일 끝까지 반복하며 중복 아이디가 있는지 확인하는 과정
          $info = fgets($fp);
          $data = json_decode($info, true); //한명씩 읽어온 json 객체를 배열의 형태로 decode

          foreach ($data as $key => $value) {
              if(trim($key) === "id"){ //동일 아이디가 있으면 메세지 출력 후 리턴
                if(trim($value) === $userid){
                  echo '<script>alert("이미 아이디가 존재합니다.")</script>';
                  echo '<script type="text/javascript">location.href = "../main.html";</script> '; 
                  exit;
                }
              }
            }
        }

        //중복아이디가 없으면 원래 파일의 제일 뒤에서 이어붙이기
        $fp = fopen("../data/member.json", "a+");
          
          fwrite($fp, $joining."\n");
          fclose($fp);

          echo '<script>alert("회원가입이 완료되었습니다.")</script>';
          echo '<script type="text/javascript">location.href = "../main.html";</script> '; 
          exit;
        }
        
        else{ //파일이 아예 없으면 바로 회원정보 입력하여 가입
            $fp = fopen("../data/member.json", "a+");
          
            fwrite($fp, $joining."\n");
            fclose($fp);          
            echo '<script>alert("회원가입이 완료되었습니다.")</script>';
            echo '<script type="text/javascript">location.href = "../main.html";</script> '; 
            exit;
        }
      
      ?>