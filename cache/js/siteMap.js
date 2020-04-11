
				$id('scoreMode0').checked=true;
				$id('newScore').value='0.1';
				$id('homeThumbScore').value='0.1';
				$id('thumbScore').value='0.1';
				$id('flashScore').value='0.1';
				$id('imgScore').value='0.1';
				$id('marInfoScore').value='0.1';
				$id('recomScore').value='0.2';
				$id('topScore').value='0.2';

				$id('isNew').checked=0;
				$id('isHomeThumb').checked=0;
				$id('isThumb').checked=0;
				$id('isFlash').checked=0;
				$id('isImg').checked=0;
				$id('isMarquee').checked=0;
				$id('isRecom').checked=0;
				$id('isTop').checked=0;

				$id('recordMaxNum').value='0';
				$id('pageMaxNum').value='5000';
				$id('hourDiff').value='8';
				$id('updateFreq').value='weekly';
				$id('updateTime0').checked=true;
				try {
					$id('baiduWap').value='0';
				}catch (e) {}
				try {
					$id('soWap').value='0';
				}catch (e) {}

				$id('oldFileNum').value='1';
				$id('lastUpdatTime').innerHTML="2016-04-19 22:36:34";
				CheckScoreMode();
				