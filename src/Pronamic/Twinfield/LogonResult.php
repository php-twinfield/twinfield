<?php

namespace Pronamic\Twinfield;

/**
 * Title: Log-on result
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class LogonResult {
	/**
	 * Log-on successful
	 * 
	 * @var string
	 */
	const OK = 'Ok';

	/**
	 * Log-on is blocked, because of system maintenance
	 * 
	 * @var string
	 */
	const BLOCKED = 'Blocked';

	/**
	 * Log-on is not trusted
	 * 
	 * @var string
	 */
	const UNTRUSTED = 'Untrusted';

	/**
	 * Log-on is invalid
	 * 
	 * @var string
	 */
	const INVALID = 'Invalid';

	/**
	 * Log-on is deleted
	 * 
	 * @var string
	 */
	const DELETED = 'Deleted';

	/**
	 * Log-on is disabled
	 * 
	 * @var string
	 */
	const DISABLED = 'Disabled';

	/**
	 * Organization is inactive
	 * 
	 * @var string
	 */
	const ORGANISATION_INACTIVE = 'OrganisationInactive';
}
