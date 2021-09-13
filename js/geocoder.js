// 地名から地図表示

/* global google*/
/* global infoWindow */

// 開催地名取得
const place = document.getElementById('place');
const place_name = place.textContent;

// 住所から地図を表示
function draw_by_address(input_address){
    // Geocoder インスタンス生成
    const geocoder = new google.maps.Geocoder();
    // Geocoderに各種設定をする
    geocoder.geocode(
        // address属性に開催地名をセット
        {'address':  input_address}, 
        // その時の挙動を設定
        function(results, status) { // 結果
            // もし地図情報が取得できたら
            if (status === google.maps.GeocoderStatus.OK) { // ステータスがOKの場合
                // id="map" の箇所にMapインスタンスを作成
                const map = new google.maps.Map(document.getElementById('map'), 
                {
                    center: results[0].geometry.location, // 地図の中心を指定
                    zoom: 15 // 地図のズームを指定
                });
                // 地図にマーカー設定
                const marker = new google.maps.Marker({
                    position: results[0].geometry.location, // マーカーを立てる位置を指定
                    map: map // マーカーを立てる地図を指定
                });
                
                // infoWindowインスタンスを作成
                const infoWindow = new google.maps.InfoWindow({ // 吹き出しの追加
                    content:"<div class='maker'>" + input_address + "</div>" // 吹き出しに表示する内容
                });
                
                infoWindow.open(map, marker); // 吹き出しの表示
            
            }else { // 失敗した場合
                alert(status);
            }
        }
    );
}

// 地名から地図表示を実行
draw_by_address(place_name);
   
