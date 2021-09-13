<!DOCTYPE html>
<html lang="ja">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>イベントページ</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
      <link rel="stylesheet" href="css/profile.css">
      <link rel="stylesheet" href="css/reset.css">
      <link rel="icon" type="image/png" href="images/favicon.png" sizes="48x48" />
      <style>
         #map {
            width: 300px;
            height: 300px;
         }
      </style>
   </head>
   <body>
      <header>
         <!-- ナビゲーションバー -->
         <nav class="navbar navbar-light fixed-top">
            <h1>Awesome&nbsp;<span>Meetup</span></h1>
            <div id="nav">
               <div class="menu-btn">
                  <span></span>
                  <span></span>
                  <span></span>
               </div>
               <div class="user-icon">
                  <img src="upload/<?=$user_icon?>">
               </div>
            </div>
         </nav>
      </header>
      <main>
         <div class="grid">
            <!--入力エラー表示-->
            <?php if($errors !== null):?>
            <ul>
               <?php foreach($errors as $error): ?>
               <li><?= $error?></li>
               <?php endforeach;?>
            </ul>
            <?php endif; ?>
            <?php if($flash_message !== null):?>
            <ul>
               <li><?= $flash_message?></li>
            </ul>
            <?php endif; ?>                        
            <div class="grid-item-left">
               <img src="upload/<?=$event->image?>" class="icon">
               <input type="hidden" name="id" value="<?=$event->id?>">
               
               <?php if(! $event->is_favorite($login_user->id)): ?>
               <form action="favorite_store.php" method="POST">
                  <input type="hidden" name="event_id" value="<?=$event->id?>">
                  <button type="subbmit">Like</button>
               </form>
               <?php else :?>
              <form action="favorite_destroy.php" method="POST">
                  <input type="hidden" name="event_id" value="<?=$event->id?>">
                  <button type="submit">いいね解除</button>
              </form>  
              <?php endif;?>
               <div>
              <p><?= count($favorites)?>いいね</p>
              <P>いいねした人の一覧</P>
                 <ul>
                     <?php foreach($favorites as $favorite): ?>
                       <li><a href="profile_show.php?id=<?=$favorite->user_id?>"><?= $favorite->name ?></a></li>
                       <?php endforeach; ?>
                 </ul>      
              </div>
            </div>
            <div class="grid-item-right">
               <div class="items">
                  <h2>Hello&nbsp;<?=$login_user->name?>さん</h2>
                  <ul>
                     <li><?=$login_user->created_at?>からユーザーサービスを利用してます</li>
                     <?php if($event->user_id == $login_user->id):?>
                     <li><a href="event_edit.php?id=<?=$event->id?>">イベントを編集</a></li>
                     <?php endif;?>
                  </ul>
               </div>
               <div class="profile">
                  <section>
                     <h2>イベント名</h2>
                     <P><?=$event->name?></P>
                  </section>
                  <section>
                     <h2>イベント内容</h2>
                     <P><?=$event->content?></P>
                  </section>
                  <section class="flex">
                     <div class="section-item">
                        <h2>イベント開催日</h2>
                        <P><?=$event->day?></P>
                     </div>
                     <div class="section-item">
                        <h2>イベント開始時間</h2>
                        <P><?=$event->time?></P>
                     </div>
                  </section>
                  <section class="flex">
                     <div class="section-item">
                        <h2>開催場所</h2>
                        <P id="place"><?= $event->place ?></P>
                        <div id="map"></div>
                     </div>
                     <div class="section-item">
                        <h2>参加人数</h2>
                        <P><?=$event->participants?>人</P>
                     </div>
                  </section>
                  
               </div>
               <ul>
                  <li>トップページへ戻りますか？</li>
                  <li><a href="top.php">トップページはこちら&#8599;</a></li>
               </ul>
               <ul>
                  <li>イベント一覧ページへ戻りますか？</li>
                  <li><a href="event_top.php">イベント一覧ページはこちら&#8599;</a></li>
               </ul>                
            </div>
         </div>
      </main>
      </form>
      <footer>     
         <h2>コメント</h2>
         <?php if(count($comments) !==0):?>
         <ul>
            <?php foreach($comments as $comment): ?>
            <li><?=$comment->name?><?=$comment->content?><?=$comment->created_at?></li>
            <li><a href="comment_destroy.php?id=<?=$comment->id?> & event_id=<?=$comment->event_id?>">コメント削除</a></li>
            <form action="comment_update.php" method="POST">
               コメント編集<input type="text" name="content" placeholder="<?=$comment->content?>"><br>
               <input type="hidden" name="id" value="<?=$comment->id?>">
               <button type="submit">コメント編集</button>
            </form>   
            <?php endforeach ;?>
         </ul>
         <?php else:?>
         <P>まだコメントはありません</P>
         <?php endif;?>
         <form action="comment_store.php" method="POST">
            コメント内容<input type="text" name="content"><br>
            <input type="hidden" name="event_id" value="<?=$event->id?>">
            <button type="submit">コメント投稿</button>            
         </form>
      </footer>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyALg70uaMcYjkzto9oPmiXyODIXCvpvAzg"></script>
      <script src="js/geocoder.js"></script>
   </body>
</html>

