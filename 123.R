setwd("c:/r_workdata")
getwd()

Sys.setlocale("LC_ALL","korean")

d1=read.csv("야구성적.csv")
d1


install.packages("lubridate")
library(lubridate)

install.packages("installr")
library(installr)

check.for.updates.R()
install.R()

d1=100
print(d1)
class(d1)
d1='100'
class(d1)
#화면에 결과 출력
print(1+1)
1+1
print(pi)
print(pi,digit=3)
print(pi,3)
print(1,3)

cat(1,":",'a','\n',2,":","b")
#숫자형
a=1
a<-2
a=1:20
a
a[1]
a[5]

a=3+(4*5)
a

1+2;2*3;

2^3
#산술연산자
#+-*
#나누기 :/,%/%,%%
#/:나누기 결과값을 실수값으로 표현
#%/% : 몫을 정수로 표현
#%%: 나머지

5%/%2

1e2
3e-2


#강제 형변환 : as.~
as.numeric('1')+as.numeric('2') 
#2. 문자형
'first' 
first='a'
first
class(first)

#3.진리값:TRUE/FALSE 
#& : and연산(모두가 참인 경우에 참:곱하기)
# | : or연산 (하나라도 참이면 참: 더하기)
# ! : not연산(반전)
3&0
3|0
!2

#4 NA/NULL
#NA: 잘못된 값이 들어올경우(NOT applicable, NOT Available)
#Null: 존재하지 않는 값

cat(1,NA,2)
cat(1,NULL,2)

sum(1,NA,2)
sum(1,NULL,2)


#5. factor형: 여러번 중복되어 나오는 데이터들을 각 값으로 모아서 대표값을 출력
#csv: 데이터 또는 컬럼을 구분하는 구분자를 ,로 해놓은 파일


t1=read.csv('factor_test.txt')
t1
#성별요약
s1=factor(t1$sex)
summary(s1)

#6.날짜와시간
#6-1 Base 패키지로 날짜 시간 제어
Sys.Date()
Sys.time()
date()

'2025-03-12'
class('2025-03-12')
#문제 : "2025-03-12"의 문자데이터를 날짜 데이터로 바꾸고 타입 확인


class(as.Date("2025-03-12"))


#날짜 형태지정
#%d :일
#%m: 월
#%Y: 년(4자리)
#%y: 년(2자리)


as.Date("03-12-2023",format="%m-%d-%Y")
as.Date("03122023",format="%m%d%y")

as.Date("2023년 3월 10일 ",format="%Y년 %m월 %d일")
#기준일자를 주고 날짜 계산
as.Date(100,origin='2025-03-12')
as.Date('2025-03-12')+100
