function togglePassword(fieldId){const el=document.getElementById(fieldId);if(!el) return;el.type = el.type === 'password' ? 'text' : 'password';}
// attach simple toggles if data-toggle attributes present
document.addEventListener('click', function(e){const t = e.target.closest('[data-toggle="password"]'); if(!t) return; const fid = t.getAttribute('data-target'); togglePassword(fid);});
