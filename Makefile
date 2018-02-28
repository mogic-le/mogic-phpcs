checkstyle:
	composer validate
	xmllint --noout Mogic/ruleset.xml
	phpcs --standard=Mogic/ruleset.xml docs/
