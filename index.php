<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TDV - Presencias en vivo</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
</head>
<body>

<nav class="admin-nav">
    <div class="brand">&#x1F6E1; TDV Seguridad</div>
</nav>

<!-- Resumen -->
<div class="summary-strip">
    <div class="summary-card">
        <div class="num num-presente"  id="cntPresente">0</div>
        <div class="lbl">En turno</div>
    </div>
    <div class="summary-card">
        <div class="num num-ausente"   id="cntAusente">0</div>
        <div class="lbl">Ausentes</div>
    </div>
    <div class="summary-card">
        <div class="num num-completado" id="cntCompletado">0</div>
        <div class="lbl">Turno completo</div>
    </div>
    <div class="summary-card" id="cardSinSalida" style="display:none;">
        <div class="num" style="color:#9a7d0a;" id="cntSinSalida">0</div>
        <div class="lbl">Registro incompleto</div>
    </div>
    <div class="summary-card">
        <div class="num num-total"     id="cntTotal">0</div>
        <div class="lbl">Total</div>
    </div>
</div>

<!-- Barra de refresh -->
<div class="refresh-bar">
    <span>Ultima actualizacion: <strong id="ultimaActz">-</strong></span>
    <div style="display:flex;align-items:center;gap:.8rem;">
        <span>Actualizando en <span class="refresh-countdown" id="countdown">30</span>s</span>
        <button class="refresh-btn" onclick="refrescar()">&#x21BB; Ahora</button>
    </div>
</div>

<!-- Grilla de tarjetas -->
<div class="cards-grid" id="cardsGrid">
    <div style="color:var(--text-muted);font-size:.9rem;grid-column:1/-1;padding:2rem;text-align:center;">
        <div class="spinner spinner-dark" style="margin:0 auto .8rem;"></div>
        Cargando presencias...
    </div>
</div>

<script>
function renderCards(guards) {
    const grid = document.getElementById('cardsGrid');
    let presente=0, ausente=0, completado=0;

    if (!guards.length) {
        grid.innerHTML = '<p style="color:var(--text-muted);grid-column:1/-1;text-align:center;">Sin empleados activos registrados.</p>';
        document.getElementById('cntPresente').textContent = 0;
        document.getElementById('cntAusente').textContent = 0;
        document.getElementById('cntCompletado').textContent = 0;
        document.getElementById('cntTotal').textContent = 0;
        document.getElementById('cardSinSalida').style.display = 'none';
        return;
    }

    const labels = {
        'presente'    : 'En turno',
        'ausente'     : 'Ausente',
        'completado'  : 'Turno completado',
        'incompleto'  : 'Registro incompleto',
        'sin-registro': 'No registró asistencia',
        'sin-salida'  : 'Registro incompleto',
        'por-iniciar' : 'Por iniciar',
        'sin-objetivos': 'Sin objetivos',
    };
    const badges = {
        'presente'    : 'badge-presente',
        'ausente'     : 'badge-ausente',
        'completado'  : 'badge-completado',
        'incompleto'  : 'badge-incompleto',
        'sin-registro': 'badge-sin-registro',
        'sin-salida'  : 'badge-sin-salida',
        'por-iniciar' : 'badge-por-iniciar',
        'sin-objetivos': 'badge-sin-objetivos',
    };

    let sinSalida = 0;
    grid.innerHTML = guards.map(g => {
        if (g.estado === 'presente')   presente++;
        if (g.estado === 'ausente')    ausente++;
        if (g.estado === 'completado') completado++;
        if (g.estado === 'sin-salida' || g.estado === 'incompleto') sinSalida++;

        const turnoTxt = (g.turno_entrada && g.turno_salida)
            ? `<span style="font-size:.72rem;color:var(--text-muted);">Turno: ${esc(g.turno_entrada)} - ${esc(g.turno_salida)} hs</span>`
            : '';

        const alertaSinSalida = g.estado === 'sin-salida' || g.estado === 'incompleto'
            ? `<div style="font-size:.75rem;color:#9a7d0a;margin-top:.4rem;font-weight:600;">
                 Registro incompleto de asistencia
               </div>`
            : '';
        const bloqueAsistencia = g.estado === 'ausente' || g.estado === 'sin-registro' || g.estado === 'por-iniciar'
            ? `<div style="font-size:.8rem;color:var(--text-muted);margin-top:.75rem;">
                   ${g.estado === 'por-iniciar' ? 'Aún no inició el turno.' : (g.estado === 'sin-registro' ? 'No registró asistencia y no tiene horario asignado.' : 'No registró asistencia hoy.')}
               </div>`
            : `<div class="gc-times" style="margin-top:.5rem;">
                <div class="gc-time-item">
                    <span class="tl">Entrada</span>
                    <span class="tv">${g.hora_entrada_hoy ? g.hora_entrada_hoy + ' hs' : '-'}</span>
                </div>
                <div class="gc-time-item">
                    <span class="tl">Salida</span>
                    <span class="tv">${g.hora_salida_hoy ? g.hora_salida_hoy + ' hs' : '-'}</span>
                </div>
            </div>`;

        return `
        <div class="guard-card ${esc(g.estado)}">
            <div class="gc-name">${esc(g.nombre)} ${esc(g.apellido)}</div>
            <div class="gc-obj">&#x1F4CD; ${esc(g.objetivo_nombre || 'Sin objetivos asignado')}</div>
            ${turnoTxt}
            <div class="gc-badge ${badges[g.estado] || 'badge-sin-objetivos'}" style="margin-top:.5rem;">
                ${labels[g.estado] || g.estado}
            </div>
            ${bloqueAsistencia}
            ${alertaSinSalida}
        </div>`;
    }).join('');

    document.getElementById('cntPresente').textContent   = presente;
    document.getElementById('cntAusente').textContent    = ausente;
    document.getElementById('cntCompletado').textContent = completado;
    document.getElementById('cntTotal').textContent      = guards.filter(g => g.id_objetivo).length;

    // Mostrar contador de sin-salida si hay alguno
    const cntSinSalida = document.getElementById('cntSinSalida');
    if (cntSinSalida) {
        cntSinSalida.textContent = sinSalida;
        document.getElementById('cardSinSalida').style.display = sinSalida > 0 ? '' : 'none';
    }
}



async function readJson(res) {
    const raw = await res.text();
    try {
        return raw ? JSON.parse(raw) : {};
    } catch (e) {
        throw new Error('La API devolvio una respuesta invalida.');
    }
}
</script>