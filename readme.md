# Nette Web Project

Base for Nette projects.
- PHP 8.2
- Nette 3.2
- PostgresSQL 16.3

## Installation
Clone repository using git
```bash
git clone https://github.com/t-ror/nette-project.git
```

Use install command
```bash
make install
```

## Commands
Start docker container
```bash
make up
```

Shutdown docker container
```bash
make down
```

Restart docker container
```bash
make restart
```

Enter to container
```bash
make exec
```

Clear cache
```bash
make rmcache
```

Run whole CI (code inspection) stack
```bash
make ci
```

CodeSniffer - checks codestyle and typehints
```bash
make cs
```

PhpStan - PHP Static Analysis
```bash
make phpstan
```

Entity mapping test
```bash
make test-entity
```

Create `diff.sql` file with database differences
```bash
make db-diff
```