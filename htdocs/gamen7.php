import React from 'react'

// gamen7.php
export default function ExchangePage() {
  return(
    <div className="p-4">
      <h1 className="text-2xl font-semibold mb-4">Mypage</h1>

      <h2 className="text-lg font-semibold mt-4">他者の感想</h2>

      <div className="flex gap-4 mt-4">
        {[1, 2, 3].map((item) =>(
          <div key={item} className="border p-4 rounded-md shadow-md w-64">
            <div>★★☆☆☆</div>
            <p>感想</p>
            <div className="flex items-center mt-2">
              <div className="bg-gray-500 rounded-full w-10 h-10 mr-2" />
              <div>名前</div>
              <div className="text-gray-500 ml-2">○○日前</div>
            </div>
            <button className="bg-blue-500 text-gray-50 px-4 py-1 rounded mt-4">
              コメント
            </button>
          </div>
        ))}
      </div>

      <div className="bg-gray-100 p-4 mt-4 rounded-md">
        <h3>実装にあたっての注意事項</h3>
        <ul className="list-decimal ml-4">
          <li>作品に対する他者の感想と何日前に書いたのかを表示</li>
          <li>その感想に対する評価とコメントボタンを表示</li>
          <li>アイコンを押すことで他者ページに移動可能</li>
        </ul>
      </div>

    </div>
  )
}
