function calculateScore() {
    let score = 0;
  
    // Ellenőrizd a válaszokat és növeld a pontszámot
    if (document.querySelector('input[name="q1"]:checked').value === 'c') {
      score++;
    }
  
    // Hasonlóképpen minden kérdéshez
  
    // Kiírás a pontszámról
    document.getElementById('score').innerText = 'Pontszám: ' + score;
  }
  