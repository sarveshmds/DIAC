// Function to know the percentage of arbitrator fees in fees calculation
function get_arbitrator_fees_percentage(caseType) {
	if (caseType == CASE_TYPE.GENERAL) {
		return GENERAL_ARB_FEES_PERCENTAGE;
	}
	if (caseType == CASE_TYPE.EMERGENCY) {
		return EMERGENCY_ARB_FEES_PERCENTAGE;
	}
	return 0;
}

function generate_arb_fees_percentage(caseType) {
	let percentage = get_arbitrator_fees_percentage(caseType);
	return `${percentage}%`;
}
