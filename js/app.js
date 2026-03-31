// app.js — 기상청 API로 날씨 조회

document.getElementById('getWeather').addEventListener('click', () => {
  const loc = document.getElementById('location').value;
  if (!loc) {
    alert('지역을 선택하세요');
    return;
  }

  const coordsMap = {
    '서울': { nx: 60,  ny: 127 },
    '부산': { nx: 98,  ny: 76  },
    '대구': { nx: 89,  ny: 90  },
    '인천': { nx: 55,  ny: 124 },
    '광주': { nx: 58,  ny: 74  },
    '대전': { nx: 67,  ny: 100 },
    '울산': { nx: 102, ny: 84  },
    '세종': { nx: 66,  ny: 103 },
    '경기': { nx: 60,  ny: 120 },
    '강원': { nx: 73,  ny: 134 },
    '충북': { nx: 69,  ny: 107 },
    '충남': { nx: 68,  ny: 100 },
    '전북': { nx: 63,  ny: 89  },
    '전남': { nx: 50,  ny: 67  },
    '경북': { nx: 89,  ny: 91  },
    '경남': { nx: 91,  ny: 77  },
    '제주': { nx: 52,  ny: 38  }
  };

  const coord = coordsMap[loc];
  if (!coord) {
    alert('해당 지역의 좌표를 찾을 수 없습니다.');
    return;
  }

  const { nx, ny } = coord;
  const apiKey   = '공공데이터포털에서 받은 인증키';
  const baseDate = new Date().toISOString().slice(0,10).replace(/-/g,'');
  const baseTime = '0600';
  const url = `http://apis.data.go.kr/1360000/VilageFcstInfoService_2.0/getUltraSrtNcst?serviceKey=${apiKey}&pageNo=1&numOfRows=10&dataType=JSON&base_date=${baseDate}&base_time=${baseTime}&nx=${nx}&ny=${ny}`;

  fetch(url)
    .then(res => {
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json();
    })
    .then(data => displayWeather(data.response.body.items.item))
    .catch(err => {
      console.error(err);
      alert('날씨 데이터를 가져오는 데 실패했습니다.');
    });
});

function displayWeather(items) {
  const container = document.getElementById('weatherDetails');
  container.innerHTML = '';
  const nameMap = {
    'RN1': '강수량 (mm)',
    'T1H': '기온 (℃)',
    'UUU': '동서바람 성분 (m/s)',
    'VVV': '남북바람 성분 (m/s)',
    'WSD': '풍속 (m/s)'
  };
  items.forEach(i => {
    const p = document.createElement('p');
    const label = nameMap[i.category] || i.category;
    p.textContent = `${label}: ${i.obsrValue}`;
    container.appendChild(p);
  });
}
