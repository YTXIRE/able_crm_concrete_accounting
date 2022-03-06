#!groovy
properties([disableConcurrentBuilds()])

pipeline {
    agent any
    options {
        buildDiscarder(logRotator(daysToKeepStr: '30', numToKeepStr: '10', artifactDaysToKeepStr: '30', artifactNumToKeepStr: '10'))
        timestamps()
    }
    stages {
        stage("Prepare docker container") {
            steps {
                echo 'Prepare docker container'
                catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                    sh 'docker stop backend_crm_app'
                }
                catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                    sh 'docker stop frontend_crm_app'
                }
                catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                    sh 'docker stop database_crm_app'
                }
                catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                    sh 'docker stop test_python_crm_app'
                }
                catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                    sh 'docker stop test_cypress_js_crm_app'
                }
                catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                    sh 'docker stop test_kotlin_crm_app'
                }
                catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                    sh 'docker rm backend_crm_app'
                }
                catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                    sh 'docker rm frontend_crm_app'
                }
                catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                    sh 'docker rm database_crm_app'
                }
                catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                    sh 'docker rm test_python_crm_app'
                }
                catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                    sh 'docker rm test_cypress_js_crm_app'
                }
                catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                    sh 'docker rm test_kotlin_crm_app'
                }
                catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                    sh 'docker rmi $(docker images -f dangling=true -q)'
                }
                catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                    sh 'docker image rm docker_pipeline_backend'
                }
                catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                    sh 'docker image rm docker_pipeline_frontend'
                }
                catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                    sh 'docker image rm mariadb'
                }
                catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                    sh 'docker image rm test_python_crm_app'
                }
                catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                    sh 'docker image rm test_cypress_js_crm_app'
                }
                catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                    sh 'docker image rm test_kotlin_crm_app'
                }
            }
        }
        stage("Create docker images") {
            steps {
                echo 'Create docker images'
                sh 'COMPOSE_DOCKER_CLI_BUILD=0 docker-compose build --parallel'
            }
        }
        stage("Create and run docker images") {
            steps {
                echo 'Create and run docker images'
                sh 'docker-compose up -d'
            }
        }
        stage("Copy config db") {
            steps {
                echo 'Copy config db'
                sh 'docker cp ./backend/config/example_db.php backend_crm_app:/var/www/html/config/db.php'
            }
        }
        stage("Run migrations") {
            steps {
                echo 'Run migrations'
                sh 'docker exec backend_crm_app php /var/www/html/yii migrate --interactive=0'
                sh 'docker exec backend_crm_app chown www-data:www-data /var/www/html/web'
                sh 'docker exec backend_crm_app chown www-data:www-data /var/www/html/web/files'
                sh 'docker exec backend_crm_app chown www-data:www-data /tmp'
            }
        }
        stage("Git clone python tests") {
            steps {
                dir('automation_tests') {
                    echo 'Git clone tests'
                    git credentialsId: 'gitlab_id',
                    branch: 'main',
                    url: 'http://192.168.0.2/root/qa-tests.git'
                    sh 'mkdir allure-results'
                }
                sh 'chmod 777 automation_tests'
            }
        }
        stage("Create docker image python tests") {
            steps {
                dir('automation_tests') {
                    echo 'Create docker image test'
                    sh 'docker build -t test_python_crm_app .'
                }
            }
        }
        stage("Run docker image python tests") {
            steps {
                script {
                    catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                        dir('automation_tests') {
                            echo 'Run docker image test'
                            sh 'chmod -R o+xw allure-results'
                            sh 'docker run -i  -v `pwd`/allure-results:/app/allure-results --network host --name test_python_crm_app test_python_crm_app'
                        }
                    }
                }
            }
        }
//         stage("Git clone cypress js tests") {
//             steps {
//                 dir('automation_tests') {
//                     sh 'ls | grep -v allure-results | xargs rm -rfv'
//                     echo 'Git clone tests'
//                     git credentialsId: 'gitlab_id',
//                     branch: 'main',
//                     url: 'http://192.168.0.2/root/able_cypress.git'
//                 }
//             }
//         }
//         stage("Create docker image cypress js tests") {
//             steps {
//                 dir('automation_tests') {
//                     echo 'Create docker image test'
//                     sh 'docker build -t test_cypress_js_crm_app .'
//                 }
//             }
//         }
//         stage("Run docker image cypress js tests") {
//             steps {
//                 script {
//                     catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
//                         dir('automation_tests') {
//                             echo 'Run docker image test'
//                             sh 'docker run -i  -v `pwd`/allure-results:/app/allure-results --network host --name test_cypress_js_crm_app test_cypress_js_crm_app'
//                         }
//                     }
//                 }
//             }
//         }
        stage("Git clone kotlin tests") {
            steps {
                dir('automation_tests') {
                    sh 'ls | grep -v allure-results | xargs rm -rfv'
                    echo 'Git clone tests'
                    git credentialsId: 'gitlab_id',
                    branch: 'main',
                    url: 'http://192.168.0.2/root/able_kotlin.git'
                }
            }
        }
        stage("Create docker image kotlin tests") {
            steps {
                dir('automation_tests') {
                    echo 'Create docker image test'
                    sh 'docker build -t test_kotlin_crm_app .'
                }
            }
        }
        stage("Run docker image kotlin tests") {
            steps {
                script {
                    catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                        dir('automation_tests') {
                            echo 'Run docker image test'
                            sh 'docker run -i  -v `pwd`/allure-results:/app/allure-results --network host --name test_kotlin_crm_app test_kotlin_crm_app'
                        }
                    }
                }
            }
        }
        stage('Generate reports') {
            steps {
                script {
                    allure([
                        includeProperties: false,
                        jdk: '',
                        properties: [],
                        reportBuildPolicy: 'ALWAYS',
                        results: [[path: 'automation_tests/allure-results']]
                    ])
                }
            }
        }
    }
    post {
        always {
            echo 'Remove docker containers'
            catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                sh 'docker stop backend_crm_app'
            }
            catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                sh 'docker stop frontend_crm_app'
            }
            catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                sh 'docker stop database_crm_app'
            }
            catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                sh 'docker stop test_python_crm_app'
            }
            catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                sh 'docker stop test_cypress_js_crm_app'
            }
            catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                sh 'docker stop test_kotlin_crm_app'
            }
            catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                sh 'docker rm backend_crm_app'
            }
            catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                sh 'docker rm frontend_crm_app'
            }
            catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                sh 'docker rm database_crm_app'
            }
            catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                sh 'docker rm test_python_crm_app'
            }
            catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                sh 'docker rm test_cypress_js_crm_app'
            }
            catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                sh 'docker rm test_kotlin_crm_app'
            }
            catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                sh 'docker rmi $(docker images -f dangling=true -q)'
            }
            catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                sh 'docker image rm docker_pipeline_backend'
            }
            catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                sh 'docker image rm docker_pipeline_frontend'
            }
            catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                sh 'docker image rm mariadb'
            }
            catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                sh 'docker image rm test_python_crm_app'
            }
            catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                sh 'docker image rm test_cypress_js_crm_app'
            }
            catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                sh 'docker image rm test_kotlin_crm_app'
            }
            echo 'Deleting workspace'
            catchError(buildResult: 'SUCCESS', stageResult: 'SUCCESS') {
                dir('automation_tests') {
                    sh "rm -rf *"
                }
                sh "rm -rf *"
            }
        }
    }
}