
  <style>

    /* Card */
    .pray-card {
      width: 100%;
      max-width: 360px;
      background: #fff;
      border-radius: 10px;
	  margin-top:8px;
      
      box-shadow: 0 6px 24px rgba(2,20,15,0.08);
      border: 2px solid rgba(5,120,110,0.06);
	  background-image: url("{{asset('assets/idcard/bg-mosque.jpg')}}");
      background-position: center; background-size: cover; background-repeat:no-repeat;
    }
	
    /* Header */
    .header {
      text-align: center;
      gap:12px;
    }
    .header .title {
      font-size: 16px;
      font-weight: 700;
      text-align: center;
      color: #0b7a66;
      line-height: 1;
      padding-top: 5px;
      margin-bottom: 5px;
    }
    .header .date {
      font-size: 14px;
	  padding: 0px  0px  5px 0px!important;
      color: #666;
      text-align: center;
    }

    /* Table grid similar to sample (3 columns layout) */
    .grid {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 0;
      border-radius: 8px;
      overflow: hidden;
      border: 2px solid #0fb3a0; /* teal border */
    }

    .cell {
      padding: 0px;
      border-right: 2px solid #0fb3a0;
      border-bottom: 2px solid #0fb3a0;
      background: transparent;
      min-height: 30px;
      display:flex;
      align-items:center;
      justify-content:center;
    }
    .cell:last-child { border-right: none; }
    /* rows: we will create rows by stacking cells in markup */

    .label {
      font-size: 14px;
      color: #0b3b34;
      font-weight: 700;
      text-align:center;
    }

    .time {
      font-size: 14px;
      color: #222;
      font-weight: 700;
      text-align:center;
    }

    /* active prayer highlight */
    .active {
      background: rgba(11,123,102,0.12);
    }

    /* footer rows for sunrise / tomorrow sunrise */
    .foot {
      margin-top: 5px;
      padding:0 5px;
      display:flex;
      justify-content:space-between;
      color:#d9534f; /* subtle red/orange for emphasis */
      font-weight:700;
      font-size:12px;
    }

    /* countdown */
    .countdown {
      margin-top:0px;
      padding-bottom: 5px;
      text-align:center;
      font-size:12px;
      color:#0b7a66;
      font-weight:700;
    }

    /* responsive */
    @media (max-width:680px) {
      .grid {
        grid-template-columns: 1fr 1fr 1fr;
      }
      /* collapse to 2-column; we will reorder cells in markup */
   .pray-card {
	  max-width: 100%;
	}
    }
    @media (max-width:420px) {
      .header .title { font-size: 16px; }
      .cell { padding:10px; min-height:45px; }
      .time { font-size:16px;}
	   .pray-card {
		  max-width: 100%;
		}
}
  </style>


  <div class="pray-card" id="prayCard">
    <div class="header">
      <div>
        <div class="title">Today's Prayer Schedule</div>
        <div class="date" id="todayDate">Loading date...</div>
      </div>
      <div id="locationInfo" style="text-align:right; font-size:14px; color:#333">Detecting location...</div>
    </div>

    <!-- Grid: we'll place cells in the visual order similar to your image.
         On desktop: [label][label][time], each row x 3 cells.
         On smaller screens grid collapses to 2 columns. -->
    <div class="grid" id="prayGrid" aria-live="polite">
      <!-- Row1: জোহর | বেলা | time -->
      <div class="cell"><div class="label">Dhuhr</div></div>
      <div class="cell"><div class="label">Noon</div></div>
      <div class="cell"><div class="time" id="DhuhrTime">--:--</div></div>

      <!-- Row2: আসর | বিকেল | time -->
      <div class="cell"><div class="label">Asr</div></div>
      <div class="cell"><div class="label">Afternoon</div></div>
      <div class="cell"><div class="time" id="AsrTime">--:--</div></div>

      <!-- Row3: মাগরিব | সন্ন্যা | time -->
      <div class="cell"><div class="label">Maghrib</div></div>
      <div class="cell"><div class="label">Evening</div></div>
      <div class="cell"><div class="time" id="MaghribTime">--:--</div></div>

      <!-- Row4: ইশা | রাত | time -->
      <div class="cell"><div class="label">Isha</div></div>
      <div class="cell"><div class="label">Night</div></div>
      <div class="cell"><div class="time" id="IshaTime">--:--</div></div>

      <!-- Row5: ফজর (ভোর) | ভোর | time -->
      <div class="cell"><div class="label">Fajr</div></div>
      <div class="cell"><div class="label">Dawn</div></div>
      <div class="cell"><div class="time" id="FajrTime">--:--</div></div>
    </div>

    <div class="foot">
      <div id="sunsetText">Today's Sunset - --:--</div>
      <div id="tomorrowSunriseText">Tomorrow's Sunrise- --:--</div>
    </div>

    <div class="countdown" id="countdown">Loading next prayer...</div>
  </div>

<script>


// mapping: API keys -> element ids we used
const prayerMap = {
  Fajr: 'FajrTime',
  Dhuhr: 'DhuhrTime',
  Asr: 'AsrTime',
  Maghrib: 'MaghribTime',
  Isha: 'IshaTime'
};

function enToBnPrayer(str) {
  const map = {
    'Fajr': 'ফজর',
    'Dhuhr': 'যোহর',
    'Asr': 'আসর',
    'Maghrib': 'মাগরিব',
    'Isha': 'ইশা'
  };

  let result = str;
  Object.keys(map).forEach(en => {
    const regex = new RegExp(en, 'gi'); // case-insensitive replace
    result = result.replace(regex, map[en]);
  });

  return result;
}
function enToBnNumber(number) {
  const en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
  const bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
  
  return number.toString().split('').map(digit => {
    const index = en.indexOf(digit);
    return index !== -1 ? bn[index] : digit;
  }).join('');
}

function formatTimeTo24(str,yes) {
	

  // API often returns '05:36 (BST)' or just '05:36'. Remove parenthesis/extra
  return str.split(' ')[0];
}

async function getUserLocation() {
  // Try navigator.geolocation; if fails -> ipapi.co fallback
  return new Promise((resolve) => {
    if (!navigator.geolocation) {
	
     	 fetch('/ip-location')
		  .then(r => r.json())
		  .then(loc => {
			  if (!loc.error) {
				  resolve({
					  lat: loc.latitude,
					  lon: loc.longitude,
					  city: loc.city,
					  source: 'ipapi'
				  });
			  } else {
				  resolve(null);
			  }
		  })
		.catch(() => resolve(null));
    }

    navigator.geolocation.getCurrentPosition(
      (pos) => {
        resolve({ lat: pos.coords.latitude, lon: pos.coords.longitude, source: '' });
      },
      async () => {
        // fallback
	
			try {
			  const r = await fetch('/ip-location');
			  const loc = await r.json();

			  if (loc && !loc.error) {
				resolve({
				  lat: loc.latitude,
				  lon: loc.longitude,
				  city: loc.city,
				  source: 'ipapi'
				});
			  } else {
				resolve(null);
			  }
			} catch (e) {
			  console.error('Error fetching IP location:', e);
			  resolve(null);
			}
      },
      { timeout: 6000 }
    );
  });
}

async function fetchTimings(lat, lon) {
  const url = `/prayer/auto?lat=${lat}&lon=${lon}`;
  const resp = await fetch(url);
  const json = await resp.json();
  if (!json || json.status !== 'success') throw new Error(json.message || 'Prayer API failed');
  return json;
}

function parseHHMMToDate(timeStr) {
  // timeStr expected "HH:MM" or "HH:MM(am/pm)" depending on API - we handle 24h or 12h
  const t = formatTimeTo24(timeStr,1);
  const [h, m] = t.split(':').map(n => parseInt(n,10));
  const d = new Date();
  d.setHours(h, m, 0, 0);
  return d;
}

function getPrayerOrder(timings) {
  // Return prayer entries in order we want to check next: Fajr, Dhuhr, Asr, Maghrib, Isha
  // Use Date objects for comparisons
  return ['Fajr','Dhuhr','Asr','Maghrib','Isha'].map(name => {
    return { name, timeStr: timings[name] ? formatTimeTo24(timings[name]) : null, dateObj: timings[name] ? parseHHMMToDate(timings[name]) : null };
  });
}

function timeDiffInSeconds(futureDate) {
  const now = new Date();
  return Math.max(0, Math.floor((futureDate - now) / 1000));
}

function secondsToHMS(sec) {
  const h = Math.floor(sec/3600);
  const m = Math.floor((sec%3600)/60);
  const s = sec%60;
  return `${h}h ${m}m ${s}s`;
}

function setGridTimes(timings) {
  // set times to elements
  for (const [k, elId] of Object.entries(prayerMap)) {
    const el = document.getElementById(elId);
    if (!el) continue;
    el.textContent = timings[k] ? formatTimeTo24(timings[k]) : '--:--';
  }
}

function highlightCurrentPrayer(timings) {
  // remove existing active classes
  document.querySelectorAll('.grid .cell').forEach(c => c.classList.remove('active'));
  const order = getPrayerOrder(timings);

  const now = new Date();
  // find current: last prayer <= now < next prayer
  let currentIndex = -1;
  for (let i=0;i<order.length;i++) {
    const p = order[i];
    if (!p.dateObj) continue;
    // compare times by minutes
    const nextIndex = (i+1) % order.length;
    const next = order[nextIndex];
    let endTime = next && next.dateObj ? next.dateObj : null;

    // Handle the case when next is Fajr (tomorrow) -> endTime add 24h
    if (next && next.name === 'Fajr' && next.dateObj) {
      endTime = new Date(next.dateObj.getTime() + 24*3600*1000);
    }

    if (p.dateObj && now >= p.dateObj && (endTime === null || now < endTime)) {
      currentIndex = i;
      break;
    }
  }

  if (currentIndex >= 0) {
    const currentName = order[currentIndex].name;
    // mark the corresponding time cell (we used id mapping)
    const id = prayerMap[currentName];
    if (id) {
      const row = document.getElementById(id).closest('.cell');
      if (row) row.classList.add('active');
    }
    return order[(currentIndex+1)%order.length]; // return next prayer info
  } else {
    // If before first prayer (Fajr) -> current is none, next is Fajr
    return order[0];
  }
}

function updateCountdownUI(nextPrayer) {
  const countdownEl = document.getElementById('countdown');
  if (!nextPrayer || !nextPrayer.dateObj) {
    countdownEl.textContent = 'Next prayer time not available';
    return;
  }
  const seconds = timeDiffInSeconds(nextPrayer.dateObj);
  countdownEl.textContent = `🕰️ Next prayer: ${nextPrayer.name} — ${secondsToHMS(seconds)}`;
}

function setSunTexts(meta) {

  const sunset = meta && meta.sunrise && meta.sunset ? meta.sunset : null;

  const sunsetEl = document.getElementById('sunsetText');
  const tomorrowSunEl = document.getElementById('tomorrowSunriseText');
  if (sunset) sunsetEl.textContent = `Today's Sunset - ${formatTimeTo24(sunset)}`;
  else sunsetEl.textContent = `Today's Sunset - --:--`;
  tomorrowSunEl.textContent = `Tomorrow's Sunrise - --:--`;
  
}

// main load function
async function loadWidget() {
  const locationInfo = document.getElementById('locationInfo');
  const todayDateEl = document.getElementById('todayDate');

  try {
    const loc = await getUserLocation();
    if (!loc) throw new Error('Unable to determine location');
    const lat = loc.lat;
    const lon = loc.lon;
    locationInfo.textContent = ``;

    const today = new Date();
    const dateStr = today.toLocaleDateString('en-EN', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
    todayDateEl.textContent = dateStr;

    const json = await fetchTimings(lat, lon);
    const timings = json.timings;
    const meta = json.meta || {};

    setGridTimes(timings);

    const sunsetVal = timings.Maghrib || timings.Sunset || null;
    if (sunsetVal) {
      document.getElementById('sunsetText').textContent = `Today's Sunset - ${formatTimeTo24(sunsetVal)}`;
    }

    try {
      const tomorrow = new Date();
      tomorrow.setDate(tomorrow.getDate()+1);
      const ts = Math.floor(tomorrow.getTime()/1000);
      const resp = await fetch(`/prayer/auto?lat=${lat}&lon=${lon}&date=${ts}`);
      const j2 = await resp.json();
      if (j2.status === 'success' && j2.timings && j2.timings.Fajr) {
        document.getElementById('tomorrowSunriseText').textContent = `Tomorrow's Sunrise - ${formatTimeTo24(j2.timings.Sunrise)}`;
      }
    } catch(e) {
      // ignore
    }

    let next = highlightCurrentPrayer(timings);

    if (!next.dateObj) {
      next.dateObj = parseHHMMToDate(next.timeStr);
      if (next.name === 'Fajr' && new Date() > next.dateObj) {
        next.dateObj = new Date(next.dateObj.getTime() + 24*3600*1000);
      }
    }

    updateCountdownUI(next);
    setInterval(() => {
      const order = getPrayerOrder(timings);
      let nextP = null;
      for (let i=0;i<order.length;i++) {
        const p = order[i];
        if (!p.dateObj) continue;
        if (p.dateObj > new Date()) { nextP = p; break; }
      }
      if (!nextP) {
        nextP = order[0];
        nextP.dateObj = new Date(nextP.dateObj.getTime() + 24*3600*1000);
      }
      highlightCurrentPrayer(timings);
      updateCountdownUI(nextP);
    }, 1000);

  } catch (err) {
    document.getElementById('prayCard').innerHTML = ``;
  }
}

loadWidget();

</script>

