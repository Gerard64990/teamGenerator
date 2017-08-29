#!"C:\Python27\python.exe"

import argparse


def team(t):
    iterations = range(2, len(t)/2+1)

    totalscore = sum(t)
    halftotalscore = totalscore/2.0

    oldmoves = {}

    for p in t:
        people_left = t[:]
        people_left.remove(p)
        oldmoves[p] = people_left

    if iterations == []:
        solution = min(map(lambda i: (abs(float(i)-halftotalscore), i), oldmoves.keys()))
        return (solution[1], sum(oldmoves[solution[1]]), oldmoves[solution[1]])

    for n in iterations:
        newmoves = {}
        for total, roster in oldmoves.iteritems():
            for p in roster:
                people_left = roster[:]
                people_left.remove(p)
                newtotal = total+p
                if newtotal > halftotalscore: continue
                newmoves[newtotal] = people_left
        oldmoves = newmoves

    solution = min(map(lambda i: (abs(float(i)-halftotalscore), i), oldmoves.keys()))
    return (solution[1], sum(oldmoves[solution[1]]), oldmoves[solution[1]])

# ===========================================================================
if __name__ == "__main__":
  caseList = []
  # print args[1]
  parser = argparse.ArgumentParser(description='TeamGenerator')
  parser.add_argument('--numbers', help='list number', metavar='TARGET_PROJECT', nargs='*', type=int)

  args = vars(parser.parse_args())
  caseList2 =  args['numbers']

  team_1 = team(caseList2)[2]
  team_2 = []
  for number in caseList2:
    if number not in team_1:
      team_2.append(number)

  team_1_str = ' '.join(str(e) for e in team_1)
  team_2_str = ' '.join(str(e) for e in team_2)

  print team_1_str + "|" + team_2_str

