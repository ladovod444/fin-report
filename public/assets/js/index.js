/**
 * Получение данных баланса счетов пользователя
 * @param userId
 * @returns {Promise<any>}
 */
const getUserData = async (userId) => {
  const api_url = '/api.php?userId=' + userId ;
  const response = await fetch(api_url, {
    method: 'GET',
  });

  if (!response.ok) {
    throw new Error(`Response status: ${response.status}`);
  }

  return await response.json();
};

/**
 * ОБновление таблицы
 * @param data
 */
function UpdateBalancesData(data) {
  // Clear data
  document.querySelector("#balances > tbody").innerHTML = "";

  // Get a reference to the table body
  const bodySection = document.querySelectorAll("tbody")[0];
  //var tableRef = bodySection


  // В цикле добавлять данные из json
  for (let i = 0; i < data.length; i++) {
    var newRow = bodySection.insertRow(i);

    // Insert a cell in the row at index 0
    var accountCell = newRow.insertCell(0);
    var monthCell = newRow.insertCell(1);
    var balanceCell = newRow.insertCell(2);

    // Append a text node to the cell
    var accountText = document.createTextNode(data[i].account);
    var MonthText = document.createTextNode(data[i].month);
    var BalanceText = document.createTextNode(data[i].balance);
    accountCell.appendChild(accountText);
    monthCell.appendChild(MonthText);
    balanceCell.appendChild(BalanceText);
  }
}

document.addEventListener('DOMContentLoaded', function (e) {
  console.log('index el');
  document.querySelector('#users').onchange = function (event) {
    console.log(event.target.value);
    if (event.target.value !== '_none') {
      const userId = event.target.value;
      getUserData(userId)
        .then(
          data => {
            console.log(data);
            UpdateBalancesData(data);

          })
        .catch((error) => {
          // @todo обработать ошибку
          console.log(error);
        });
    }
    else {
      document.querySelector("#balances > tbody").innerHTML = "";
    }

  };

});


