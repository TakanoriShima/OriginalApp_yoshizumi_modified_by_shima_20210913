<?php

    // コメントの設計図を作る
    require_once "models/Model.php";
    require_once "models/Event.php";
    class Comment extends Model {
        // プロパティ;
        public $id;
        public $user_id; 
        public $event_id;
        public $content;
        public $created_at;
        // コンストラクタ
        public function __construct($user_id='', $event_id='',$content=''){
            // プロパティの初期化
            $this->user_id = $user_id;
            $this->event_id= $event_id;
            $this->content = $content;
        }
        
        // 入力チェックを行うメソッド
        public function validate(){
            // エラー配列を作成
            $errors = array();
            //もしコメント内容が入力されていれば
            if($this->content ===''){
                $errors[]="コメントを入力してください";
            }
            // 完成したエラー配列をはい、あげる
            return $errors;
        }
            // 全テーブル情報を取得するメソッド
            public static function all(){
                try {
                    $pdo = self::get_connection();
                    $stmt = $pdo->query('SELECT * FROM comments WHERE id=:id');
                    // フェッチの結果を、Commentクラスのインスタンスにマッピングする
                    $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Comment');
                    $comments = $stmt->fetchAll();
                    self::close_connection($pdo, $stmp);
                    // Commentクラスのインスタンスの配列を返す
                    return $comments;
                } catch (PDOException $e) {
                    return 'PDO exception: ' . $e->getMessage();
                }
            }
            
            //自分自身の情報をDBに保存するメソッドを作ろう
            // データを1件登録するメソッド
            public function save(){
                try {
                    $pdo = self::get_connection();
                    
                    //新規登録の時
                    if($this->id === null){
                        $stmt = $pdo -> prepare("INSERT INTO comments (user_id, event_id,content) VALUES (:user_id, :event_id,:content)");//変数値を保持しているのでprepare
                        // バインド処理
                        $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
                        $stmt->bindParam(':event_id', $this->event_id, PDO::PARAM_INT);
                        $stmt->bindParam(':content', $this->content, PDO::PARAM_STR);
                        $stmt->execute();
                        self::close_connection($pdo, $stmp);
                        return "コメント投稿が成功しました";
                    }else{ //更新処理
                        $stmt = $pdo -> prepare("UPDATE posts SET title=:title, content=:content,image=:image WHERE id=:id");//変数値を保持しているのでprepare
                        $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
                        $stmt->bindParam(':event_id', $this->event_id, PDO::PARAM_INT);
                        $stmt->bindParam(':content', $this->content, PDO::PARAM_STR);
                        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT); 
                        // 実行
                        $stmt->execute();
                        self::close_connection($pdo, $stmp);
                        return $this->id."番目の投稿情報を更新しました";
                        
                    }
                    
                } catch (PDOException $e) {
                    return 'PDO exception: ' . $e->getMessage();
                }
            }
            //投稿番号からPostオブジェクトを取得するメソッド
            public static function find($id){
            
                try {
                    $pdo = self::get_connection();
                    $stmt = $pdo -> prepare("SELECT posts.id,posts.user_id,users.name,posts.title,posts.content,posts.image,posts.created_at FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id=:id");//変数値を保持しているのでprepare
                    // バインド処理
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    // 実行
                    $stmt->execute();
                    
                    // フェッチの結果を、Userクラスのインスタンスにマッピングする
                    $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Post');
                    // Postクラスのインスタンスを返す
                    $post = $stmt->fetch();  //ひとり抜き出し
                    self::close_connection($pdo, $stmp);
                    return $post;                    
                    
                } catch (PDOException $e) {
                    return 'PDO exception: ' . $e->getMessage();
                }
            }
                public static function destroy($id){
             try {
                    $pdo = self::get_connection();

                        $stmt = $pdo -> prepare("DELETE FROM comments WHERE id=:id");//変数値を保持しているのでprepare
                        // バインド処理
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->execute();
                        self::close_connection($pdo, $stmp);
                        return "コメント削除しました";
                  } catch (PDOException $e) {
                    return 'PDO exception: ' . $e->getMessage();
                 }               
                    
                }
                //ログインメソッド
                public static function login($email,$password){
            
                try {
                    $pdo = self::get_connection();
                    $stmt = $pdo -> prepare("SELECT * FROM users WHERE email=:email AND password=:password");//変数値を保持しているのでprepare
                    // バインド処理
                    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                    // 実行
                    $stmt->execute();
                    
                    // フェッチの結果を、Userクラスのインスタンスにマッピングする
                    $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Post');
                    // Userクラスのインスタンスを返す
                    $user = $stmt->fetch();  //ひとり抜き出し
                    self::close_connection($pdo, $stmp);
                    return $user;                    
                    
                } catch (PDOException $e) {
                    return 'PDO exception: ' . $e->getMessage();
                }
            }
                
            
}

    
    
    