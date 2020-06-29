#!/usr/bin/env bash
aws ec2 describe-images --filters Name=name,Values=ubuntu/images/hvm-ssd/ubuntu*   --query 'Images[*].[CreationDate,Name,VirtualizationType,Public,ImageId]'   --output text | sort -k1 -r | head -n10
