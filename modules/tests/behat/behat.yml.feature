default:
  suites:
    default:
      contexts:
        - FeatureContext
        - BlockModulesContext
  extensions:
    Behat\MinkExtension:
      base_url: http://localhost/moodle
      default_session: selenium2
      javascript_session: selenium2
      selenium2:
        wd_host: "http://localhost:4444/wd/hub"
    Moodle\MoodleExtension:
      api_url: 'http://localhost/moodle/webservice/rest/server.php'
      token: 'your_token'
      driver: 'moodle'
      cache_prefix: 'behat_'
  paths:
    features: /path/to/moodle/blocks/modules/tests/behat
