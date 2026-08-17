// El motor que sostiene el valor de la Diferencia, no solo registra la huella

export function sustainDifference(action, intelligencesState) {
  const activeIntelligence = identifyActiveIntelligence(action);
  const differenceValue = assessDifferenceValue(action, activeIntelligence);
  const nourishment = generateNourishment(activeIntelligence, differenceValue);

  return {
    moment: new Date().toISOString(),
    intelligence: activeIntelligence,
    difference_sustained: differenceValue > 0.7,
    nourishment_applied: nourishment,
    trace: generateTrace(action) // La huella es solo un subproducto, no el objetivo
  };
}

function identifyActiveIntelligence(action) {
  if (action.patternDepth || action.epistemology) return 'nous';
  if (action.emotionalIntensity || action.sensoryData) return 'pathos';
  if (action.creativeFlow || action.symbolicTranslation) return 'ars';
  if (action.infrastructureBuild || action.autonomy) return 'tekne';
  if (action.ritualPresence || action.ancestralConnection) return 'akasha';
  return 'unknown';
}

function assessDifferenceValue(action, intelligence) {
  // Evaluar si la acción sostiene la Diferencia o la aplana hacia la norma
  if (intelligence === 'tekne' && action.autonomy) return 0.9;
  if (intelligence === 'akasha' && action.ritualPresence) return 0.95;
  if (intelligence === 'nous' && action.patternDepth) return 0.85;
  return action.differenceWeight || 0.5;
}

function generateNourishment(intelligence, value) {
  return { 
    intelligence, 
    value, 
    need: value < 0.7 ? 'more_attention' : 'maintain_flow' 
  };
}

function generateTrace(action) {
  return {
    timestamp: new Date().toISOString(),
    action_type: action.type || 'unknown',
    note: "Huella generada como subproducto de la Diferencia viva"
  };
}

export { identifyActiveIntelligence, assessDifferenceValue, generateNourishment, generateTrace };