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

<!-- Grilla de tarjetas -->
<div class="cards-grid" id="cardsGrid">
    <div style="color:var(--text-muted);font-size:.9rem;grid-column:1/-1;padding:2rem;text-align:center;">
        <div class="spinner spinner-dark" style="margin:0 auto .8rem;"></div>
        Cargando presencias...
    </div>
</div>

<script>

</script>