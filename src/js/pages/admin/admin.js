
import '../../../sass/admin/admin.scss';

const tx = document.getElementsByTagName('textarea');

for (let i = 0; i < tx.length; i++) {
    tx[i].setAttribute('style', 'height:' + (tx[i].scrollHeight) + 'px;');
    tx[i].addEventListener('input', (event) => {
        event.target .style.height = 0;
        event.target .style.height = (event.target .scrollHeight) + 'px';
    }, false);
}
