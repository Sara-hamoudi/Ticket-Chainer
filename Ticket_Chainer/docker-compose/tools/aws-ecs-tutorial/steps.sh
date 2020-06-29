#!/usr/bin/env bash
ecs-cli configure --cluster ec2-tutorial --default-launch-type EC2 --config-name ec2-tutorial --region eu-west-1
ecs-cli configure profile --access-key AKIAYXI6HP32C3J2QIAP --secret-key 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 --profile-name ec2-tutorial-profile
ecs-cli up --keypair TicketChainerAWS --capability-iam --size 2 --instance-type t2.micro --cluster-config ec2-tutorial --ecs-profile ec2-tutorial-profile
ecs-cli compose up --create-log-groups --cluster-config ec2-tutorial --ecs-profile ec2-tutorial-profile
ecs-cli ps --cluster-config ec2-tutorial --ecs-profile ec2-tutorial-profile


# show keypairs in sepcific region
# aws ec2 describe-key-pairs --region eu-west-1



# https://docs.aws.amazon.com/fr_fr/AmazonECS/latest/developerguide/ecs-cli-tutorial-ec2.html
# TicketChainer Staging
ecs-cli configure --cluster ticketchainer-staging --default-launch-type EC2 --config-name ticketchainer-staging --region eu-west-1
ecs-cli configure profile --access-key AKIAYXI6HP32C3J2QIAP --secret-key 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 --profile-name ticketchainer-staging-profile
ecs-cli up --keypair TicketChainerAWS --capability-iam --size 1 --instance-type t2.micro --cluster-config ticketchainer-staging --ecs-profile ticketchainer-staging-profile
ecs-cli compose up --create-log-groups --cluster-config ticketchainer-staging --ecs-profile ticketchainer-staging-profile
ecs-cli ps --cluster-config ticketchainer-staging --ecs-profile ticketchainer-staging-profile
ecs-cli compose down --cluster-config ticketchainer-staging --ecs-profile ticketchainer-staging-profile


ecs-cli compose service rm --cluster-config ticketchainer-staging --ecs-profile ticketchainer-staging-profile
ecs-cli down --force --cluster-config ticketchainer-staging  --ecs-profile ticketchainer-staging -profile