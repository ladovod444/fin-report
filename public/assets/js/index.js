/**
 * Получение данных баланса счетов пользователя
 * @param userId
 * @returns {Promise<any>}
 */
const getUserData = async (userId) => {
  //console.log(data.access_token)
  const api_url = '/api.php?userId=' + userId ;
  const response = await fetch(api_url, {
    method: 'GET',
  });

  if (!response.ok) {
    throw new Error(`Response status: ${response.status}`);
  }

  return await response.json();
};

document.addEventListener('DOMContentLoaded', function (e) {
  console.log('index el');
  document.querySelector('#users').onchange = function (event) {
    console.log(event.target.value);
    const userId = event.target.value;
    getUserData(userId)
      .then(
        data => {
          console.log(data);
          //setProduct(data)
        })
      .catch((error) => {
        // @todo обработать ошибку
        console.log(error);
      });

  };

});


