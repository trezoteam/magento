#!/bin/bash

#This script execute test command, verify if it really fails and return the correct exit code.

if [ $# -ne 1 ] 
then	
   echo "To run this script you must provide test command between quotes as the first argument!"
   exit 1
fi   

exec 5>&1
TEST_OUT=$(php $1 | tee /dev/fd/5; exit ${PIPESTATUS[0]})
if [ $? -eq 1 ] 
then
    FAIL=$(echo $TEST_OUT | grep -i "failed" | wc -l);	
    if [ $FAIL -gt 0 ]
    then 
       exit 1
    fi           
fi
exit 0
